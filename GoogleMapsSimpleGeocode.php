<?php
/**
 * GoogleMapsSimpleGeocode
 *
 * Класс для реализации геокодирования (преобразования адресов в географические координаты)
 * с использованием сервиса геокодирования Google Maps Geocoding Service.
 *
 * @package GoogleMapsSimpleGeocode
 * @author  dZ <mail@dotzero.ru>
 * @version 0.3 (9-dec-2010)
 * @link	http://dotzero.ru
 * @link	https://github.com/dotzero/GoogleMapsSimpleGeocode/
 * @link    http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/
 */
class GoogleMapsSimpleGeocode
{
    /**
     * Экземпляр класса
     */
    protected static $instance = null;

    /**
     * Параметры запросов геокодирования
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/#GeocodingRequests
     */
    private $GGeoAddress = null;
    private $GGeoApiKey = null;
    private $GGeoSensor = false;
    private $GGeoOutput = 'csv';
    private $GGeoEncoding = 'utf8';

    /**
     * Не отформатированный ответ сервиса геокодирования
     */
    private $GGeoRawResponse = null;

    /**
     * Описание ошибки на любом из этапов геокодирования
     */
    private $GGeoError = null;

    /**
     * Коды состояния
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/#StatusCodes
     */
    private $GGeoStatusCode = array(
        '200' => 'Ошибок не произошло, адрес был успешно проанализирован, и геокод был возвращен.',
        '500' => 'Запрос на геокодирование маршрута не может быть успешно обработан, но точная причина сбоя неизвестна.',
        '601' => 'Был указан незаполненный или некорректный адрес.',
        '602' => 'Невозможно найти географическую точку, соответствующую указанному адресу.',
        '603' => 'Геокод для указанного адреса или трасса для запрошенный маршрута не могут быть выданы по юридическим причинам.',
        '610' => 'Заданный ключ недействителен или не соответствует домену, для которого он был задан.',
        '620' => 'Превзойден предел запросов для заданного ключа за текущий 24-часовой период.'
    );

    /**
     * Точность геокодирования
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/#GeocodingAccuracy
     */
    private $GGeoAccuracy = array(
        0 => 'Точность неизвестна',
        1 => 'Точность на уровне страны',
        2 => 'Точность на уровне региона (штат, область, префектура и т. д.) уровень точности',
        3 => 'Точность на уровне составных частей регионов (район, муниципалитет и т. д.) уровень точности',
        4 => 'Точность на уровне города (поселка)',
        5 => 'Точность на уровне почтового индекса',
        6 => 'Точность на уровне улицы',
        7 => 'Точность на уровне перекрестка',
        8 => 'Точность на уровне адреса',
        9 => 'Точность на уровне здания (название постройки, дома, торговый центр, и т.–д.) уровень точности'
    );

    /**
     * Описание сообщений об ошибках
     */
    const MSG_BAD_API            = 'Пустой Google Maps Api ключ';
    const MSG_BAD_ADDRESS        = 'Был указан незаполненный адрес';
    const MSG_UNKNOWN_CODE       = 'Был возвращен неизвестный код состояния';
    const MSG_FAIL_SEND_REQUEST  = 'Не удается подключится к сервису геокодирования';

    /**
     * Флаги критичности ошибок
     */
    const STOP_MESSAGE  = 0;
    const STOP_CONTINUE = 1;
    const STOP_CRITICAL = 2;

    /**
     * Доступен только один экземпляр класса GoogleMapsSimpleGeocode
     * доступ через GoogleMapsSimpleGeocode::getInstance()
     */
    private function __construct() { /* private */ }
    private function __clone() { /* private */ }

    /**
     * Обращение/создание экземпляра класса GoogleMapsSimpleGeocode
     */
    public static function getInstance()
    {
        return (self::$instance === null) ? self::$instance = new self() : self::$instance;
    }

    /**
     * Основной метод для геокодирования адреса
     *
     * @param bool $raw
     * @return mixed
     */
    public function search($raw = false)
    {
        try
        {
            $this->sendRequest($this->buildUrl());

            return ($raw) ? $this->GGeoRawResponse : $this->parseResult();
        }
        catch (GeocodeException $e)
        {
            $this->GGeoError = $e->getMessage();

            return false;
        }
    }

    /**
     * Формирование запроса на геокодирование
     *
     * @example http://maps.google.com/maps/geo?q=1600+Amphitheatre+Parkway&output=json&oe=utf8&sensor=false&key=api_key
     * @return string
     */
    private function buildUrl()
    {
        $geocodeUrl  = 'http://maps.google.com/maps/geo?';

        $geocodeUrl .= 'q='.urlencode($this->getAddress());
        $geocodeUrl .= "&output=".$this->GGeoOutput;
        $geocodeUrl .= "&oe=".$this->GGeoEncoding;
        $geocodeUrl .= "&sensor=" . $this->GGeoSensor;
        $geocodeUrl .= "&key=".$this->getApiKey();

        return $geocodeUrl;
    }

    /**
     * Получение результата запроса от сервиса геокодирования
     *
     * @param string $url
     */
    private function sendRequest($url)
    {
        if(!$result = file_get_contents($url))
        {
            throw new GeocodeException(self::MSG_FAIL_SEND_REQUEST, self::STOP_CRITICAL);
        }

        $this->GGeoRawResponse = $result;
    }

    /**
     * Возвращает текст ошибки
     *
     * @return mixed
     */
    public function errorMessage()
    {
        return $this->GGeoError;
    }

    /**
     * Установка адреса, который нужно геокодировать
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $address = preg_replace('/[^a-zа-я0-9., -]+/iu', '', $address);
        $this->GGeoAddress = $address;
    }

    /**
     * Получение адреса, который нужно геокодировать
     *
     * @return string
     */
    public function getAddress()
    {
        if(empty($this->GGeoAddress))
        {
            throw new GeocodeException(self::MSG_BAD_ADDRESS, self::STOP_CRITICAL);
        }

        return $this->GGeoAddress;
    }

    /**
     * Установка Google Api Key
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/signup.html
     * @param string $apikey
     */
    public function setApiKey($apikey)
    {
        $this->GGeoApiKey = $apikey;
    }

    /**
     * Получение Google Api Key
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/signup.html
     * @return string
     */
    private function getApiKey()
    {
        if(empty($this->GGeoApiKey))
        {
            throw new GeocodeException(self::MSG_BAD_API, self::STOP_CRITICAL);
        }

        return $this->GGeoApiKey;
    }

    /**
     * Исходит ли запрос на геокодирование от устройства с датчиком местоположения
     *
     * @param bool $flag
     */
    public function setSensor($flag = false)
    {
        $this->GGeoSensor = ($flag) ? true : false;
    }

    /**
     * Установка формата ответа Службы геокодирования
     *
     * @param string $format
     */
    public function serOutput($format)
    {
        $availableFormats = array('xml', 'csv', 'json');

        if(in_array($format, $availableFormats))
        {
            $this->GGeoOutput = $format;
        }
    }

    /**
     * Установка формата кодировки результатов
     *
     * @param string $charset
     */
    public function setEncoding($charset = 'utf8')
    {
        $this->GGeoEncoding = $charset;
    }

    /**
     * На основе выбранного формата выбирает метод разбора ответа
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/#GeocodingResponses
     * @return mixed
     */
    private function parseResult()
    {
        switch ($this->GGeoOutput)
        {
            case 'xml'  : return $this->parseXML();
            case 'csv'  : return $this->parseCSV();
            case 'json' : return $this->parseJSON();
        }
    }

    /**
     * Разбор ответа в формате CSV
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/#CSV
     * @return
     */
    private function parseCSV()
    {
        list($code, $accuracy, $longitude, $latitude) = explode(',', $this->GGeoRawResponse);

        $result = array();

        $result['code'] = $code;
        $result['code_message'] = $this->parseReturnCode($code);

        if(intval($code) == 200)
        {
            $result['accuracy'] = $accuracy;
            $result['accuracy_message'] = $this->parseAccuracyCode($accuracy);

            $result['latitude'] = $latitude;
            $result['longitude'] = $longitude;
        }
        else
        {
            throw new GeocodeException($result['code_message'], self::STOP_MESSAGE);
        }

        return $result;
    }

    /**
     * Разбор ответа в формате XML
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/#KML
     * @return
     */
    private function parseXML()
    {
        $responce = simplexml_load_string($this->GGeoRawResponse);

        $result = array();

        $result['code'] = intval($responce->Response->Status->code);
        $result['code_message'] = $this->parseReturnCode($result['code']);

        if($result['code'] == 200)
        {
            foreach($responce->Response->Placemark AS $placemarkObj)
            {
                $placemark = array();

                $placemark['address'] = (string) $placemarkObj->address;

                $placemark['accuracy'] = intval($placemarkObj->AddressDetails['Accuracy']);
                $placemark['accuracy_message'] = $this->parseAccuracyCode($placemark['accuracy']);

                $coordinates = explode(',', (string) $placemarkObj->Point->coordinates);
                $placemark['latitude'] = $coordinates[0];
                $placemark['longitude'] = $coordinates[1];

                $result['placemarks'][] = $placemark;
            }
        }
        else
        {
            throw new GeocodeException($result['code_message'], self::STOP_MESSAGE);
        }

        return $result;
    }

    /**
     * Разбор ответа в формате JSON
     *
     * @link http://code.google.com/intl/ru-RU/apis/maps/documentation/geocoding/#JSON
     * @return
     */
    private function parseJSON()
    {
        $responce = json_decode($this->GGeoRawResponse);

        $result = array();

        $result['code'] = intval($responce->Status->code);
        $result['code_message'] = $this->parseReturnCode($result['code']);

        if($result['code'] == 200)
        {
            foreach($responce->Placemark AS $placemarkObj)
            {
                $placemark = array();

                $placemark['address'] = (string) $placemarkObj->address;

                $placemark['accuracy'] = intval($placemarkObj->AddressDetails->Accuracy);
                $placemark['accuracy_message'] = $this->parseAccuracyCode($placemark['accuracy']);

                $placemark['latitude'] = $placemarkObj->Point->coordinates[0];
                $placemark['longitude'] = $placemarkObj->Point->coordinates[1];

                $result['placemarks'][] = $placemark;
            }
        }
        else
        {
            throw new GeocodeException($result['code_message'], self::STOP_MESSAGE);
        }

        return $result;
    }

    /**
     * Возвращает Описание по коду состояния
     *
     * @param integer $code
     * @return string
     */
    private function parseReturnCode($code)
    {
        if(array_key_exists($code, $this->GGeoStatusCode))
        {
            return $this->GGeoStatusCode[$code];
        }
        else
        {
            return self::MSG_UNKNOWN_CODE;
        }
    }

    /**
     * Возвращает Описание точности геокодирования
     *
     * @param integer $code
     * @return string
     */
    private function parseAccuracyCode($accuracy)
    {
        if(array_key_exists($accuracy, $this->GGeoAccuracy))
        {
            return $this->GGeoAccuracy[$accuracy];
        }
        else
        {
            return $this->GGeoAccuracy[0];
        }
    }
}

class GeocodeException extends Exception
{
    public function __construct($msg, $code, $response = null)
    {
        parent::__construct($msg, $code);
    }
}
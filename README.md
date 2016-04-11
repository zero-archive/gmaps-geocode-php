Google Maps Simple Geocode
==========================
[![Build Status](https://travis-ci.org/dotzero/gmaps-geocode-php.svg?branch=master)](https://travis-ci.org/dotzero/gmaps-geocode-php)

Класс для реализации геокодирования (преобразования адресов в географические координаты)
с использованием сервиса геокодирования Google Maps Geocoding.

### Основные методы класса

Обращаение к экземпляру класса

    GoogleMapsSimpleGeocode::getInstance();

Основной метод для геокодирования адреса по установленным параметрам.
При использовании параметра `$raw = true` вернет ответ сервиса без обработки.

    search($raw = false)

В случае возникновения ошибки метод будет хранить текст ошибки.

    errorMessage()

### Методы для установки параметром геокодирования

Установка адреса, который нужно геокодировать

    setAddress($address)

Установка Google Api Key

    setApiKey($apikey)

Исходит ли запрос на геокодирование от устройства с датчиком местоположения

    setSensor($flag = false)

Установка формата ответа Службы геокодирования. Доступные форматы: `xml`, `csv`, `json`.
По-умолчанию установлен `csv`.

    setOutput($format)

Установка формата кодировки результатов

    setEncoding($charset = 'utf8')

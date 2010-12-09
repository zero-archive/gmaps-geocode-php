<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>GoogleMapsSimpleGeocode example</title>
</head>
<body>
    <form action="" method="get">
        <input name="address" size="40" value="<?=$_GET['address']?>" /><br />
        <input type="submit" value="Искать!" />
    </form>

    <?php

    require_once 'GoogleMapsSimpleGeocode.php';

    if(isset($_GET['address']) and strlen($_GET['address']) > 2)
    {
        $geo = GoogleMapsSimpleGeocode::getInstance();

        $geo->setAddress($_GET['address']);
        $geo->setApiKey('YOU_API_KEY');
        $geo->serOutput('csv');
        $geo->setEncoding('utf8');

        if($result = $geo->search())
        {
            foreach($result AS $title => $value)
            {
                printf('%s - %s<br>', $title, print_r($value, true));
            }
        }
        else
        {
            echo '<b>Ошибка:</b> '.$geo->errorMessage();
        }
    }
    ?>

</body>
</html>
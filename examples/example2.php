<html>
<head>
    <meta content="text/html; charset=utf8" http-equiv="Content-Type"/>
    <title>GoogleMapsSimpleGeocode example 2</title>
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
        $geo->setApiKey('API_KEY');

        // CSV
        $geo->setOutput('csv');

        if($result = $geo->search(true))
        {
            echo '<h3>CSV Raw response</h3><textarea rows="5" cols="80" name="text">'.$result.'</textarea>';
        }

        // JSON
        $geo->setOutput('json');

        if($result = $geo->search(true))
        {
            echo '<h3>JSON Raw response</h3><textarea rows="20" cols="80" name="text">'.$result.'</textarea>';
        }

        // JSON
        $geo->setOutput('xml');

        if($result = $geo->search(true))
        {
            echo '<h3>XML Raw response</h3><textarea rows="20" cols="80" name="text">'.$result.'</textarea>';
        }

    }
    ?>

</body>
</html>
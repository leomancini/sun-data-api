<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json;');

    $dateInput = $_GET['date'];
    $date = new DateTime($dateInput);

    $locations = [
        'NewYorkCity' => [
            'lat' => 40.730610,
            'lng' => -73.935242
        ]
    ];

    $lat = $locations['NewYorkCity']['lat'];
    $lng = $locations['NewYorkCity']['lng'];

    date_default_timezone_set('UTC');
    $sunInfoISO8601 = [];
    $sunInfo = date_sun_info(strtotime($dateInput), $lat, $lng);
    foreach ($sunInfo as $key => $value) {
        $sunInfoISO8601[$key] = date(DATE_ISO8601, $value);
    }

    $sunriseTime = date_sunrise(strtotime($dateInput), SUNFUNCS_RET_TIMESTAMP, $lat, $lng);
    $sunsetTime = date_sunset(strtotime($dateInput), SUNFUNCS_RET_TIMESTAMP, $lat, $lng);

    if ($_GET['timezone'] === 'ET') {
        date_default_timezone_set('America/New_York');

        $dateSunsetTimeUTC = new DateTime();
        $dateSunsetTimeUTC->setTimestamp($sunsetTime);
        $dateSunsetTimeUTC->setTimezone(new DateTimeZone('America/New_York'));
        $dateSunsetTimeET = new DateTime($dateSunsetTimeUTC->format('Y-m-d H:i'));

        echo json_encode([
            'results' => $dateSunsetTimeET,
            'timestamp' => $sunsetTime,
            'status' => 'OK'
        ]);
    } else {
        echo json_encode([
            'results' => $sunInfoISO8601,
            'status' => 'OK'
        ]);
    }
?>

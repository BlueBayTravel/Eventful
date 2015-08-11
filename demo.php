<?php

require_once 'src/Eventful.php';

$eV = new Eventful('ENTER_APP_KEY');

$evLogin = $eV->login('USERNAME', 'PASSWORD');
if ($evLogin) {
    $evArgs = [
        'location' => 'Mexico',
    ];

    $cEvent = $eV->call('events/search', $evArgs);

    var_dump($cEvent);
} else {
    die('<strong>Error logging into Eventful API</strong>');
}

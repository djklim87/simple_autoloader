<?php

use App\Services\Curl;
use App\Services\Exchange;
use App\Services\Saver;

require_once '../vendor/autoload.php';
$exchange = new Exchange(new Curl(), new Saver());
$exchange->run();
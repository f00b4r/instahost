<?php declare(strict_types = 1);

use App\Utils;

require __DIR__ . '/../vendor/autoload.php';

Utils::dotenv();
Utils::debugger();
Utils::json(['version' => '1.0']);

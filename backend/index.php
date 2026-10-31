<?php

// L9it Backend Entry Point
// Simple PHP router for the API

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

$app = new App\Application();
$app->run();

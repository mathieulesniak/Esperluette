<?php
/**
 * Esperluette - App entry point
 *
 * @package  Esperluette
 * @author   Mathieu LESNIAK <mathieu@lesniak.fr>
 */

// Use composer autoloader
require '../vendor/autoload.php';

// or use Suricate autoloader

$app = new Suricate\Suricate('../app/config/app.ini');
$app->run();

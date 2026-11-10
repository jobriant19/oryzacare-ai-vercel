<?php

$tmp = '/tmp/laravel';
foreach (['storage/framework/views', 'storage/framework/cache', 'storage/logs', 'bootstrap/cache'] as $dir) {
    if (! is_dir("$tmp/$dir")) {
        mkdir("$tmp/$dir", 0777, true);
    }
}

$env = [
    'LARAVEL_STORAGE_PATH' => "$tmp/storage",
    'VIEW_COMPILED_PATH'   => "$tmp/storage/framework/views",
    'APP_SERVICES_CACHE'   => "$tmp/bootstrap/cache/services.php",
    'APP_PACKAGES_CACHE'   => "$tmp/bootstrap/cache/packages.php",
    'APP_CONFIG_CACHE'     => "$tmp/bootstrap/cache/config.php",
    'APP_ROUTES_CACHE'     => "$tmp/bootstrap/cache/routes.php",
    'APP_EVENTS_CACHE'     => "$tmp/bootstrap/cache/events.php",
    'MYSQL_ATTR_SSL_CA'    => dirname(__DIR__) . '/certs/aiven-ca.pem',
];
foreach ($env as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $_SERVER[$key] = $value;
}

require __DIR__ . '/../public/index.php';

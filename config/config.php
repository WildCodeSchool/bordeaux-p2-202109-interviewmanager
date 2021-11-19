<?php

define('ENV', getenv('ENV') ? getenv('ENV') : 'dev');

//Model (for connexion data, see unversionned db.php)
define('DB_USER', getenv('DB_USER') ? getenv('DB_USER') : APP_DB_USER);
define('DB_PASSWORD', getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : APP_DB_PASSWORD);
define('DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') : APP_DB_HOST);
define('DB_NAME', getenv('DB_NAME') ? getenv('DB_NAME') : APP_DB_NAME);
define('GIT_SECRET', '63e89d4425232147d19ff7e5eedd03db35bd3987');
define('GIT_CLIENT', 'cdcf8baf65a1aa0b4dba');
define('REDIRECT_URI', 'http://localhost:8000//loggithub');

//VIew
define('APP_VIEW_PATH', __DIR__ . '/../src/View/');

define('HOME_PAGE', 'home/index');

// database dump file path for automatic import
define('DB_DUMP_PATH', __DIR__ . '/../database.sql');

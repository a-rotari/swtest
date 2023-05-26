<?php

// Database parameters: update them according to your database settings
// define('DB_HOST', 'localhost');
define('DB_HOST', 'mysql');
define('DB_USER', 'root');
define('DB_PASS', 'root_password');
define('DB_NAME', 'swtest');

//APP root and URL Root: update them according to your settings
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost');

//Product types: update according to your products. Keys are used to populate submit form field IDs,
// values are for text displayed in add product page product selector
define('PRODUCTTYPES', ['furniture' => 'Furniture', 'book' => 'Book', 'dvd' => 'DVD',]);
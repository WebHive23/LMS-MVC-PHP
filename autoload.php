<?php

// Include configuration files
foreach (glob("../config/*.php") as $filename) {
    require $filename;
}


// Include core classes
foreach (glob("../core/*.php") as $filename) {
    require $filename;
}

APP_DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

// Register autoloader function to load classes dynamically
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = '../' . str_replace('\\', '/', $class) . '.php';

    // Check if file exists and require it
    if (file_exists($file)) {
        require $file;
    }
});
    
// Start App
$app = new App;
$app->run();

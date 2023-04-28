<?php

use App\Core\Router;

class App
{
    /**
     * Run the application.
     */
    public function run()
    {
        // Include the routes file
        require '../routes/web.php';

        // Route the request
        $path = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        Router::route($_SERVER['REQUEST_METHOD'], $path);
    }
}

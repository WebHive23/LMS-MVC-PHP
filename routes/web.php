<?php

use App\Controllers\UserController;
use App\Core\Router;

Router::get('/', [new UserController(), 'index']);

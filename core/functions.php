<?php

function view($name, $data = [])
{
    if (!empty($data))
        extract($data);

    $filename = "../app/views/" . $name . ".view.php";
    if (file_exists($filename)) {
        require_once $filename;
    } else {
        redirect('error/500');
    }
}

function redirect($path)
{
    header("Location: " . APP_URL . "/" . $path);
    die;
}

function back()
{
    header("Location: {$_SERVER['HTTP_REFERER']}");
}


function resources($path) {
    return "../resources/" . $path;
}
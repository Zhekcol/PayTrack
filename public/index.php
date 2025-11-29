<?php

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

switch ($url) {
    case '':
        require_once __DIR__ . '/../views/home.php';
        break;

    case 'auth/register':
        require_once __DIR__ . '/../views/auth/register.php';
        break;

    case 'auth/login':
        require_once __DIR__ . '/../views/auth/login.php';
        break;

    case 'auth/register_action':
    require_once __DIR__ . '/../views/actions/register_action.php';
    break;


    default:
        echo "404 - Página no encontrada";
        break;
}

<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: /auth/login");
    exit;
}
$base_url = "/PayTrack/public/";

// Evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$usuario = $_SESSION['usuario'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PayTrack</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>css/graficas.css">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row min-vh-100">

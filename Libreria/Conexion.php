<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect("localhost", "root", "", "registro_visitas");
mysqli_set_charset($connection, "utf8mb4");

if(!$connection){
    die( "error de depuración: " . mysqli_connect_error());
}

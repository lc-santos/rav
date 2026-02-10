-- Active: 1761340476403@@127.0.0.1@3306
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "rav";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>

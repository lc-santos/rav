<?php
$host = "localhost";
$user = "root"; 
$pass = "";     
$db   = "cadastro";  // nome do banco

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

date_default_timezone_set('America/Sao_Paulo');
?>
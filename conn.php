<?php
// config.php
$host = 'localhost';
$db   = 'rav2'; // Atualizado conforme seu novo print
$user = 'root'; 
$pass = 'Nirvana898!'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
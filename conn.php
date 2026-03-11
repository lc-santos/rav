<?php
// Configurações do banco de dados
$host = "localhost";
$dbname = "rav3"; // Certifique-se de usar o nome do banco que criamos
$username = "root";
$password = "Nirvana898!"; // Sua senha do MySQL

try {
    // Criação da conexão via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configura para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mantém compatibilidade com códigos antigos que usam $conn (mysqli)
    $conn = new mysqli($host, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

} catch (PDOException $e) {
    die("Falha na conexão com o banco de dados: " . $e->getMessage());
}
?>
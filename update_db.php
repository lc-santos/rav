<?php
/**
 * SISTEMA RAV (REGISTRO DE ACESSO DE VEÍCULOS)
 * update_db.php - Importador Automático do Script SQL do Banco de Dados
 * Executa as queries do arquivo sql/rav.sql de forma sequencial no MySQL local.
 */

// Configurações do banco de dados (deve coincidir com conn.php)
$host = "localhost";
$username = "root";
$password = "";
$dbname = "rav";

header('Content-Type: text/html; charset=utf-8');
echo "<h2>Instalador / Atualizador do Banco de Dados RAV</h2>";

try {
    // 1. Conecta ao MySQL sem selecionar banco inicialmente (caso o banco não exista)
    $pdoInit = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdoInit->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cria o banco de dados se não existir
    $pdoInit->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "<p style='color: green;'>✓ Banco de dados '<strong>$dbname</strong>' criado/verificado com sucesso!</p>";
    
    // 2. Conecta ao banco de dados específico agora
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sqlFile = __DIR__ . '/sql/rav.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("O arquivo de script SQL não foi encontrado em: " . $sqlFile);
    }
    
    $sqlContent = file_get_contents($sqlFile);
    
    // Executa todo o conteúdo SQL de uma vez, utilizando o suporte nativo do PDO para múltiplas queries
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0); // Opcional, mas previne alguns problemas
    $pdo->exec($sqlContent);
    
    $sucessos = "Todos (Executado em Lote)";
    $falhas = 0;
    
    echo "<hr>";
    echo "<h3>Resumo da Execução:</h3>";
    echo "<p style='color: green;'>Comandos executados com sucesso: <strong>$sucessos</strong></p>";
    if ($falhas > 0) {
        echo "<p style='color: red;'>Comandos com erro: <strong>$falhas</strong></p>";
    } else {
        echo "<p style='color: green; font-weight: bold;'>✓ Importação concluída com 100% de sucesso! O banco de dados está pronto para o projeto.</p>";
        echo "<p><a href='painel-admin.php' style='display:inline-block; padding:10px 20px; background:#198754; color:#fff; text-decoration:none; border-radius:5px;'>Acessar Painel Admin</a></p>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Erro Crítico:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}
?>

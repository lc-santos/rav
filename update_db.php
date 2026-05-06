<?php
require_once 'conn.php';
try {
    $pdo->exec("ALTER TABLE registros_acesso ADD COLUMN curso VARCHAR(50) NULL AFTER tipo_acesso;");
    $pdo->exec("ALTER TABLE registros_acesso ADD COLUMN periodo VARCHAR(50) NULL AFTER curso;");
    $pdo->exec("ALTER TABLE registros_acesso ADD COLUMN funcao VARCHAR(50) NULL AFTER periodo;");
    echo "Columns added successfully.";
} catch(PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        try {
            $pdo->exec("ALTER TABLE registros_acesso ADD COLUMN funcao VARCHAR(50) NULL AFTER periodo;");
            echo "Added funcao column.";
        } catch(PDOException $e2) {
            echo "Error adding funcao: " . $e2->getMessage();
        }
    } else {
        echo "Error: " . $e->getMessage();
    }
}
try {
    $pdo->exec("ALTER TABLE registros_acesso MODIFY COLUMN tipo_acesso VARCHAR(50) NOT NULL;");
} catch(Exception $e) {}
?>

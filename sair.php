<?php
session_start(); // Inicia a sessão para poder destruí-la
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destrói a sessão

// Redireciona para a página inicial (index.php) do projeto rav
header("Location: index.php");
exit();
?>  
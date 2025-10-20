<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table border="1" width="100%">
        <tr>
            <th>Id</th>
            <th>Nome da Empresa</th>
            <th>Tipo de Documento</th>
            <th>Documento</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>Nome do ADMIN</th>
            <th>CPF Admin</th>
            <th>Data de Nascimento</th>
            <th>Email do Admin</th>
            <th colspan="2">Ações</th>
        </tr>
        <?php
        include 'conn.php';
        $select = "SELECT * FROM dados_empresa";
        $result = mysqli_query($conn, $select);
        while ($dados_empresa = mysqli_fetch_assoc($result)) {
            $id = $dados_empresa['id'];
            $empresaNome = $dados_empresa['empresaNome'];
            $tipoDocumento = $dados_empresa['tipoDocumento'];
            $documento = $dados_empresa['documento'];
            $telefone = $dados_empresa['telefone'];
            $endereco = $dados_empresa['endereco'];
            $nomeAdmin = $dados_empresa['nomeAdmin'];
            $cpfadmin = $dados_empresa['cpfadmin'];
            $datanasc = $dados_empresa['datanasc'];
            $emailAdmin = $dados_empresa['emailAdmin'];
            echo "
            <tr>
                <td>$id</td>
                <td>$empresaNome</td>
                <td>$tipoDocumento</td>
                <td>$documento</td>
                <td>$telefone</td>
                <td>$endereco</td>
                <td>$nomeAdmin</td>
                <td>$cpfadmin</td>
                <td>$datanasc</td>
                <td>$emailAdmin</td>
                <td><a href='#'>Editar</a></td>
                <td><a href='#'>Excluir</a></td
            </tr>";
        }
        ?>
    </table>

    <a href="index.php"><button>Voltar</button></a>

</html>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - RAV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="css/cadastro.css">
</head>

<body class="d-flex flex-column min-vh-100">

  <header id="header" class="border-bottom margin-bottom py-3">
    <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center">
      <a href="index.php"><h1 class="h4 mb-2 mb-sm-0 text-center text-sm-start fw-bold">RAV - Registro de acesso de veículos</h1></a>
      <nav class="d-flex gap-3">
        <a href="login.php" class="text-decoration-none fw-bold padding-right">Acessar</a>
        <a href="cadastro.php" class="text-decoration-none fw-bold padding-right">Cadastrar</a>
        <a href="saibamais.php" class="text-decoration-none fw-bold">Saiba mais</a>
      </nav>
    </div>
  </header>

  <main class="container-fluid flex-grow-1 d-flex align-items-center justify-content-center py-5 margin-top">

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">

          <div class="inter-container p-4 p-md-5">
            <h2 class="text-center mb-4">Cadastro de Empresa e Usuário</h2>

            <form action="processa-cadastro.php" method="POST">
              <div class="row">
                <div class="col-lg-6">
                  <h4 class="mb-3">Empresa</h4>
                  <div class="mb-3">
                    <label for="empresaNome" class="form-label">Nome da Empresa</label>
                    <input type="text" class="form-control" id="empresaNome" name="empresaNome" placeholder="Nome da Empresa" required>
                  </div>
                  <div class="mb-3">
                    <label for="tipoDocumento" class="form-label">Tipo de Documento</label>
                    <input type="text" class="form-control" id="tipoDocumento" name="tipoDocumento" placeholder="Tipo de Documento (ex: CNPJ)" required>
                  </div>
                  <div class="mb-3">
                    <label for="documento" class="form-label">Número do Documento</label>
                    <input type="text" class="form-control" id="documento" name="documento" placeholder="Número do Documento" required>
                  </div>
                  <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone" required>
                  </div>
                  <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço" required>
                  </div>
                </div>

                <div class="col-lg-6">
                  <h4 class="mb-3">Usuário</h4>
                  <div class="mb-3">
                    <label for="nome_completo" class="form-label">Nome completo</label>
                    <input type="text" class="form-control" id="nome_completo" name="nome_completo" placeholder="Nome completo" required>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                  </div>
                  <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                  </div>
                  <div class="mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>
                  </div>
                  <div class="mb-3">
                    <label for="datanasc" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="datanasc" name="datanasc" placeholder="Data de Nascimento">
                  </div>
                </div>
              </div>

              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <small>Todos os direitos reservados - 2025</small>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
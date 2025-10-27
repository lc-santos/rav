<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - RAV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
</head>

<body class="d-flex flex-column min-vh-100">

  <header>
    <h1>Guarita</h1>
    <nav>
      <a href="login.php">Login</a>
      <a href="cadastro.php">Cadastro</a>
      <a href="#">Saiba mais</a>
    </nav>
  </header>

  <main class="container-fluid flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">

              <h2 class="text-center mb-4">Login</h2>

              <form action="processa-login.php" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">E-mail</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                </div>

                <div class="mb-3">
                  <label for="senha" class="form-label">Senha</label>
                  <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                </div>

                <div class="d-grid mt-4"> <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                </div>

                <a href="cadastro.php" class="d-block text-center mt-3">Criar conta</a>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RAV - Registro de acesso de veículos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">

  <script>
    //mudar a cor do header
    window.addEventListener('scroll', function() {
      const header = document.getElementById('header');
      const scrollTop = window.scrollY;
      if (scrollTop > 200) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  </script>

</head>

<body class="d-flex flex-column min-vh-100">

  <header id="header" class="border-bottom py-3">
    <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center">
      <a href="index.php"><h1 class="h4 mb-2 mb-sm-0 text-center text-sm-start fw-bold">RAV - Registro de acesso de veículos</h1></a>
      <nav class="d-flex gap-3">
        <a href="login.php" class="text-decoration-none fw-bold padding-right">Acessar</a>
        <a href="cadastro.php" class="text-decoration-none fw-bold padding-right">Cadastrar</a>
        <a href="saibamais.php" class="text-decoration-none fw-bold">Saiba mais</a>
      </nav>
    </div>
  </header>

  <main class="container-fluid flex-grow-1 d-flex align-items-center justify-content-center margin-top">
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


  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <small>Todos os direitos reservados - 2025</small>
  </footer>

</body>

</html>
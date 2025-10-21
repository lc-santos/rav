<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guarita</title>
  <link rel="stylesheet" href="css/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

  <header>
    <h1><a href="index.php">Guarita</h1></a>
    <nav>
      <a href="login.php">Login</a>
      <a href="cadastro.php">Cadastro</a>
      <a href="#">Saiba mais</a>
    </nav>
  </header>


  <section class="container">
    <div class="form-container">
        <h1>Login</h1>
        <form method="post" action="processa-login.php">
            
            <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
            </div>
    
            <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>
    
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    

    </div>

  </section>

 
  <footer>
    todos os direitos reservados - 2025
  </footer>

</body>

</html>
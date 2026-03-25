<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>G-TEC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/index.css">

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

  <header id="header" class="border-bottom margin-bottom py-3">
    <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center">
      <h1 class="h4 mb-2 mb-sm-0 text-center text-sm-start fw-bold">G-TEC</h1>
      <nav class="d-flex gap-3">
        <a href="login.php" class="text-decoration-none fw-bold padding-right">Acessar</a>
        <a href="saibamais.php" class="text-decoration-none fw-bold">Saiba mais</a>
      </nav>
    </div>
  </header>

  <main class="flex-grow-1 min-margin-top margin-bottom default-bg">

    <section class="div-principal py-5 default-bg margin-top">
      <div class="container default-bg">
        <div class="row align-items-center text-center text-md-start default-bg">

          <div class="col-12 col-md-5 div-principal-text mb-4 mb-lg-0 default-bg">
            <h2 class="fw-bold fs-1 mb-4 default-bg">
              Gerenciamento de acesso ETEC
            </h2>
            <p class="fw-bold">
              Sistema web prático e dinâmico.
            </p>

            <div class="default-bg div-principal-buttons d-flex flex-column flex-sm-row justify-content-center justify-content-md-start gap-4 mt-3">
              <a href="login.php" class="btn btn-primary px-4">Acessar</a>
            </div>
          </div>

          <div class="col-12 col-md-6 text-center div-principal-image mb-4 mb-lg-0 default-bg">
            <img src="img/acesso.png" alt="Homem usando o sistema RAV" class="img-fluid blue-border">
          </div>

        </div>
      </div>
    </section>

    <hr>




    <section class="py-5">
      <div class="container">
        <div class="row align-items-center text-center text-md-start">

          <div class="col-12 col-md-6 mb-4 mb-lg-4 col-lg-6">
            <ul class="list-unstyled fs-4 mb-lg-4 justify-content-center">
              <li>Registros dinâmicos</li>
              <li>Cadastros simplificados</li>
              <li>Relatórios automatizados</li>
              <li>Sem precisar de aparelhos externos!</li>
            </ul>
          </div>

          <div class="col-12 col-md-6">
            <div class="ratio ratio-16x9">
              <iframe src="https://www.youtube.com/embed/gfzsjcTZlWU?si=n10mSSJwvYUY0Mqv"
                title="YouTube video player"
                allowfullscreen></iframe>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5">
      <div class="container">
        <div class="row align-items-center">

          <div class="col-12 col-md-5 order-2 order-md-1">
            <img src="img/guarda2.png" alt="Segurança" class="img-fluid green-border">
          </div>

          <div class="col-12 col-md-7 text-center text-md-start order-1 order-md-2">
            <p class="mb-6">
              Gerencie e atribua diferentes acessos e funções<br>
              para funcionários da portaria
            </p>
          </div>

        </div>
      </div>
    </section> 


  </main>


  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <small>Todos os direitos reservados - 2025</small>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
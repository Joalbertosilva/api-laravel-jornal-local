<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'Jornal Local' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom" data-bs-theme="light">
    <div class="container">
      <a class="navbar-brand fw-bold" href="/">🗞️ Jornal Campestre</a>
      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse" data-bs-target="#navbarMain"
              aria-controls="navbarMain" aria-expanded="false" aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMain">
        <!-- VISITANTE -->
        <ul class="navbar-nav ms-auto" id="nav-guest">
          <li class="nav-item"><a class="nav-link" href="{{ route('register.form') }}">Registrar</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('login.form') }}">Entrar</a></li>
        </ul>

        <!-- LOGADO -->
        <ul class="navbar-nav ms-auto d-none align-items-center gap-3" id="nav-auth">
          <li class="nav-item"><a class="nav-link" href="/publish">Publicar</a></li>
          <li class="nav-item">
            <span class="navbar-text">Olá, <b id="nav-user-name"></b></span>
          </li>
          <li class="nav-item">
            <button class="btn btn-outline-secondary btn-sm" id="btn-logout">Sair</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ALERTA NO TOPO -->
  <div class="container mt-3" id="top-alert"></div>

  <!-- CONTEÚDO PRINCIPAL -->
  <main class="container py-3">
    @yield('content')
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


  <script>
    document.addEventListener("DOMContentLoaded", () => {
    console.log("✅ DOM completamente carregado!");
    console.log("Script carregado com sucesso!");

    const guest = document.getElementById('nav-guest');
    const auth = document.getElementById('nav-auth');
    const nameEl = document.getElementById('nav-user-name');
    const logoutBtn = document.getElementById('btn-logout');

    console.log("guest:", guest);
    console.log("auth:", auth);
    console.log("nameEl:", nameEl);

    // Verifica se os elementos foram encontrados
    if (!guest || !auth) {
      console.error("⚠️ Elementos de navegação não foram encontrados no DOM!");
      return;
    }

    // Simulação do armazenamento local de autenticação
    const authStore = {
      get() {
        try {
          const authData = localStorage.getItem('auth');
          return authData ? JSON.parse(authData) : null;
        } catch (e) {
          console.error('Erro ao ler do localStorage:', e);
          return null;
        }
      },
      set(v) {
        localStorage.setItem('auth', JSON.stringify(v));
      },
      clear() {
        localStorage.removeItem('auth');
      },
    };

    // Atualiza o menu conforme o status de login
    function updateNav() {
      const a = authStore.get();
      const logged = !!a?.user;

      guest.classList.toggle('d-none', logged);
      auth.classList.toggle('d-none', !logged);

      if (logged && nameEl) {
        nameEl.textContent = a.user.name || a.user.email || 'Usuário';
      }
    }

    // Exibe alerta no topo da tela
    function showTopAlert(html, type = 'success') {
      const host = document.getElementById('top-alert');
      if (!host) return;
      host.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${html}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    }

    // Botão de logout
    logoutBtn?.addEventListener('click', () => {
      authStore.clear();
      updateNav();
      showTopAlert('Você saiu da sua conta.', 'secondary');
    });

    // Inicializa o estado do menu
    updateNav();
  });

  </script>

  @yield('scripts')
</body>
</html>

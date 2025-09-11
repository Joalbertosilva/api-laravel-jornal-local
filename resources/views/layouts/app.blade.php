<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'Jornal Local' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom" data-bs-theme="light">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">🗞️ Jornal Local</a>

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
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

<div class="container mt-3" id="top-alert"></div>

<main class="container py-3">
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // estado simples guardado no localStorage
  const authStore = {
    get(){ try { return JSON.parse(localStorage.getItem('auth')||'null'); } catch { return null; } },
    set(v){ localStorage.setItem('auth', JSON.stringify(v)); },
    clear(){ localStorage.removeItem('auth'); }
  };

  function updateNav() {
    const a = authStore.get();
    const guest = document.getElementById('nav-guest');
    const auth  = document.getElementById('nav-auth');
    const nameEl = document.getElementById('nav-user-name');
    const logged = !!a?.user;

    guest.classList.toggle('d-none', logged);
    auth.classList.toggle('d-none', !logged);
    if (logged && nameEl) nameEl.textContent = a.user.name || a.user.email || 'Usuário';
  }

  function showTopAlert(html, type='success'){
    document.getElementById('top-alert').innerHTML =
      `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
         ${html}
         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
       </div>`;
  }

  document.addEventListener('DOMContentLoaded', ()=>{
    updateNav();

    // mostra alerta se vier ?logged=1
    const p = new URLSearchParams(location.search);
    if (p.get('logged') === '1') showTopAlert('Login realizado com sucesso!', 'success');

    document.getElementById('btn-logout')?.addEventListener('click', ()=>{
      authStore.clear(); updateNav(); showTopAlert('Você saiu da sua conta.', 'secondary');
    });
  });
</script>
</body>
</html>

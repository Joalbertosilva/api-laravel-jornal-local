@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">Entrar</h4>

        <div id="loginAlert"></div>

        <form id="loginForm" autocomplete="off">
          <div class="mb-2">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" id="logEmail" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" class="form-control" name="password" id="logPass" required>
          </div>
          <button class="btn btn-primary w-100" type="submit" id="btnLogin">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function logAlert(html, type='danger'){
  document.getElementById('loginAlert').innerHTML =
    `<div class="alert alert-${type} mt-3">${html}</div>`;
}

document.getElementById('loginForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const btn = document.getElementById('btnLogin');
  btn.disabled = true; btn.textContent = 'Entrando…';

  const payload = {
    email: document.getElementById('logEmail').value.trim(),
    password: document.getElementById('logPass').value
  };

  try {
    const res = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
      body: JSON.stringify(payload)
    });

    const data = await res.json().catch(()=> ({}));

    if (res.ok) {
      // salva estado e redireciona para home com alerta
      localStorage.setItem('auth', JSON.stringify({ user: data.user, token: data.token || null }));
      window.location.href = '/?logged=1';
    } else {
      // 401/422 etc.
      const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('<br>') : 'Erro ao entrar.');
      logAlert(msg, 'danger');
    }
  } catch {
    logAlert('Falha de rede. Tente novamente.', 'danger');
  } finally {
    btn.disabled = false; btn.textContent = 'Login';
  }
});
</script>
@endsection

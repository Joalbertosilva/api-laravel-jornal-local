@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">Criar conta</h4>

        <div id="registerAlert"></div>

        <form id="registerForm" autocomplete="off">
          <div class="mb-2">
            <label class="form-label">Nome</label>
            <input class="form-control" name="name" required>
          </div>
          <div class="mb-2">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Senha</label>
            <input type="password" class="form-control" name="password" autocomplete="new-password" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirmar senha</label>
            <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password" required>
          </div>
          <button class="btn btn-primary w-100" type="submit" id="btnReg">Registrar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
const show = (html, type='success') =>
  document.getElementById('registerAlert').innerHTML =
    `<div class="alert alert-${type} mt-3">${html}</div>`;

document.getElementById('registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('btnReg');
  btn.disabled = true; btn.textContent = 'Enviando…';

  const fd = new FormData(e.target);
  const payload = Object.fromEntries(fd.entries());

  try {
    const res = await fetch('/api/register', {
      method: 'POST',
      headers: {'Content-Type': 'application/json', 'Accept':'application/json'},
      body: JSON.stringify(payload)
    });
    const data = await res.json().catch(() => ({}));

    if (res.ok) {
      show(`Bem-vindo, <b>${data.user?.name}</b>!`, 'success');
      localStorage.setItem('auth', JSON.stringify({user: data.user}));
      if (typeof updateNavFromAuth === 'function') updateNavFromAuth();
    } else {
      const errs = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || res.status);
      show(errs, 'danger');
    }
  } catch (err) {
    show('Falha na requisição. Tente novamente.', 'danger');
  } finally {
    btn.disabled = false; btn.textContent = 'Registrar';
  }
});
</script>
@endsection

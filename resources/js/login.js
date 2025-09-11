const logAlert = (html, type='success') =>
  document.getElementById('loginAlert').innerHTML =
    `<div class="alert alert-${type}">${html}</div>`;

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
      method:'POST',
      headers:{'Content-Type':'application/json','Accept':'application/json'},
      body: JSON.stringify(payload)
    });

    const data = await res.json().catch(()=> ({}));

    if (res.ok) {
      logAlert('Login realizado!', 'success');
      // salva estado "logado" para o topo mostrar Olá, Fulano
      localStorage.setItem('auth', JSON.stringify({ user: data.user, token: data.token || null }));
      if (typeof updateNavFromAuth === 'function') updateNavFromAuth();
      setTimeout(()=> location.href='/', 800);
    } else {
      logAlert(data.message || 'Credenciais inválidas.', 'danger');
    }
  } catch {
    logAlert('Falha de rede. Tente novamente.', 'danger');
  } finally {
    btn.disabled = false; btn.textContent = 'Login';
  }
});

const regAlert = (html, type='success') =>
  document.getElementById('registerAlert').innerHTML = `<div class="alert alert-${type}">${html}</div>`;

document.getElementById('registerForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const btn = document.getElementById('btnReg');
  btn.disabled = true; btn.textContent = 'Enviando…';

  const payload = {
    name: document.getElementById('regName').value.trim(),
    email: document.getElementById('regEmail').value.trim(),
    password: document.getElementById('regPass').value,
    password_confirmation: document.getElementById('regPass2').value
  };

  try{
    const res = await fetch('/api/register', {
      method:'POST',
      headers:{'Content-Type':'application/json','Accept':'application/json'},
      body: JSON.stringify(payload)
    });
    const data = await res.json().catch(()=> ({}));
    if(res.ok){
      regAlert(`Bem-vindo, <b>${data.user?.name || payload.name}</b>!`, 'success');
      authStore.set({user: data.user || {name: payload.name, email: payload.email}, token: data.token || null});
      updateNavFromAuth();
      setTimeout(()=> location.href='/', 1000);
    }else{
      regAlert(data.message || 'Erro ao registrar.', 'danger');
    }
  }catch{
    regAlert('Falha de rede. Tente novamente.', 'danger');
  }finally{
    btn.disabled = false; btn.textContent = 'Registrar';
  }
});

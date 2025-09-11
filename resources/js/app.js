import './bootstrap';

const authStore = {
  get(){ try{return JSON.parse(localStorage.getItem('auth')||'null')}catch{return null}},
  set(v){ localStorage.setItem('auth', JSON.stringify(v)); },
  clear(){ localStorage.removeItem('auth'); }
};

function updateNavFromAuth(){
  const a = authStore.get();
  const guest = document.getElementById('nav-guest');
  const auth  = document.getElementById('nav-auth');
  if(!guest || !auth) return;
  const logged = !!a?.token || !!a?.user;
  guest.classList.toggle('d-none', logged);
  auth.classList.toggle('d-none', !logged);
  if (logged) {
    document.getElementById('nav-user-name').textContent = a?.user?.name || a?.user?.email || 'Usuário';
  }
}
document.addEventListener('DOMContentLoaded', updateNavFromAuth);

document.addEventListener('click', async (e)=>{
  if (e.target.id === 'btn-logout'){
    const a = authStore.get();
    authStore.clear();
    updateNavFromAuth();
    // se /api/logout existir, tenta chamar (ignora erro)
    if (a?.token) {
      fetch('/api/logout',{method:'POST',headers:{'Authorization':'Bearer '+a.token}}).catch(()=>{});
    }
  }
});

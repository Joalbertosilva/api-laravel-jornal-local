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

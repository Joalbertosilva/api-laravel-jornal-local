@extends('layouts.app')

@section('content')
  <section class="p-4 p-md-5 bg-white rounded-3 shadow-sm mb-4">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="fw-bold mb-2">Notícias do seu bairro, em tempo real</h1>
        <p class="text-muted mb-3">Publique, comente e descubra o que acontece na sua cidade.</p>
        <a class="btn btn-primary me-2" href="/publish">Publicar notícia</a>
        <a class="btn btn-outline-secondary" href="{{ route('register.form') }}">Criar conta</a>
      </div>
      <div class="col-md-4 d-none d-md-block">
        <div class="text-center display-1">📰</div>
      </div>
    </div>
  </section>

  <section>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Destaques</h4>
      <form class="d-flex gap-2" onsubmit="return false;">
        <input id="q" class="form-control" placeholder="Buscar artigos…">
        <button id="btn-search" class="btn btn-outline-primary">Buscar</button>
      </form>
    </div>

    <div id="articles" class="row g-3">
      <div class="col-12 text-center text-muted py-5" id="articles-empty">Carregando…</div>
    </div>
  </section>

  <script>
    async function loadArticles(query='') {
      const cont = document.getElementById('articles');
      const empty = document.getElementById('articles-empty');
      empty?.classList.remove('d-none');
      cont.innerHTML = '';
      try {
        const url = '/api/articles' + (query ? ('?q=' + encodeURIComponent(query)) : '');
        const res = await fetch(url, {headers:{'Accept':'application/json'}});
        const data = await res.json();
        const list = Array.isArray(data) ? data : (data.data || []);
        if (!list.length) {
          empty.textContent = 'Nenhum artigo encontrado.';
          return;
        }
        empty?.classList.add('d-none');
        cont.innerHTML = list.map(a => `
          <div class="col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <span class="badge bg-secondary mb-2">${a.category ?? 'geral'}</span>
                <h5 class="card-title">${a.title}</h5>
                <p class="card-text text-muted">${(a.summary || '').toString().slice(0,120)}${(a.summary||'').length>120?'…':''}</p>
              </div>
              <div class="card-footer bg-white border-0">
                <small class="text-muted">por ${a.author?.name ?? 'Anônimo'}</small>
              </div>
            </div>
          </div>
        `).join('');
      } catch {
        empty.textContent = 'Falha ao carregar artigos.';
      }
    }
    document.getElementById('btn-search')?.addEventListener('click', () => loadArticles(document.getElementById('q').value));
    document.addEventListener('DOMContentLoaded', () => loadArticles());
  </script>
@endsection

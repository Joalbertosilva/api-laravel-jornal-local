@extends('welcome')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">Publicar notícia</h4>
        <form id="pubForm">
          <div class="mb-2">
            <label class="form-label">User ID</label>
            <input class="form-control" name="user_id" placeholder="ex.: 1" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Título</label>
            <input class="form-control" name="title" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Resumo</label>
            <input class="form-control" name="summary">
          </div>
          <div class="mb-2">
            <label class="form-label">Categoria</label>
            <input class="form-control" name="category" placeholder="cidades, esportes..." required>
          </div>
          <div class="mb-2">
            <label class="form-label">Conteúdo</label>
            <textarea class="form-control" rows="6" name="content" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
              <option value="draft">Rascunho</option>
              <option value="published">Publicado</option>
            </select>
          </div>
          <button class="btn btn-primary">Publicar</button>
        </form>
        <div id="pubResult" class="mt-3 small text-muted"></div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('pubForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const payload = Object.fromEntries(fd.entries());
  const res = await fetch('/api/articles', {
    method: 'POST',
    headers: {
      'Content-Type':'application/json',
      'Accept':'application/json',
    },
    body: JSON.stringify(payload)
  });
  const out = document.getElementById('pubResult');
  const data = await res.json().catch(()=>({}));
  out.innerText = res.ok ? 'Publicado! ID: ' + data.id : 'Erro: ' + (data.message || res.status);
});
</script>
@endsection

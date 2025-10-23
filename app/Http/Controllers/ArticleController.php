<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Retorna todos os artigos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de artigos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro no servidor"
     *     )
     * )
     */
    public function index()
    {
        try {
            // Busca artigos publicados e ordena por data de publicação
            $articles = Article::with(['author', 'comments', 'reactions'])
                ->orderByDesc('published_at')
                ->get();

            return response()->json($articles, 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar artigos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar artigos'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Cria um novo artigo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "title", "slug", "content"},
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="slug", type="string"),
     *             @OA\Property(property="content", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Artigo criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Falha na validação"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|unique:articles,slug',
                'summary' => 'nullable|string',
                'content' => 'required|string',
                'category' => 'nullable|string|max:100',
                'status' => 'nullable|string|in:draft,published',
                'published_at' => 'nullable|date',
                'cover_path' => 'nullable|string',
            ]);

            $article = Article::create($validated);
            return response()->json($article, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar artigo: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar artigo'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     summary="Exibe um artigo específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artigo encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Artigo não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $article = Article::with(['author', 'comments', 'reactions'])->find($id);

        if (!$article) {
            return response()->json(['error' => 'Artigo não encontrado'], 404);
        }

        return response()->json($article);
    }

    /**
     * @OA\Put(
     *     path="/api/articles/{id}",
     *     summary="Atualiza um artigo existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artigo atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Artigo não encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['error' => 'Artigo não encontrado'], 404);
        }

        $article->update($request->all());
        return response()->json($article);
    }

    /**
     * @OA\Delete(
     *     path="/api/articles/{id}",
     *     summary="Deleta um artigo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Artigo deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Artigo não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['error' => 'Artigo não encontrado'], 404);
        }

        $article->delete();
        return response()->json(['message' => 'Artigo deletado com sucesso']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Minha API",
 *     version="1.0.0",
 *     description="Documentação da API"
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     required={"id", "title", "slug", "content", "status", "user_id"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="summary", type="string"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="category", type="string"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
 *     @OA\Property(property="view_count", type="integer"),
 *     @OA\Property(property="cover_path", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Swagger extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/swagger",
     *      operationId="getSwagger",
     *      tags={"Swagger"},
     *      summary="Swagger",
     *      description="Swagger",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *     )
     */
    public function index()
    {
        return view('swagger');
    }

    public function handle(){
        $openapi = \OpenApi\Generator::scan([config('l5-swagger.documentations.routes')]);
        file_put_contents(storage_path('api-docs/api-docs.json'), $openapi->toJson());
        $this->info('Swagger documentation generated successfully.');
    }

}
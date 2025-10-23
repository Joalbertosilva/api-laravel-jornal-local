<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class ExampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/example",
     *     summary="Get an example data",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="string")
     *         )
     *     )
     * )
     */
    public function getExampleData(Request $request)
    {
        return response()->json(['data' => 'Example data']);
    }
}

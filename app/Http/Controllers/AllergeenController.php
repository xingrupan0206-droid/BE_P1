<?php

namespace App\Http\Controllers;

use App\Models\Allergeen;
use Illuminate\Http\JsonResponse;

class AllergeenController extends Controller
{
    public function show(int $productId): JsonResponse
    {
        $allergenen = Allergeen::getAllergeen($productId);

        return response()->json($allergenen);
    }
}

<?php

namespace App\Http\Controllers;

use App\Utils\Utils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ValidatorController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyImage(Request $request): JsonResponse
    {
        $storageImage = Utils::getStorageImage($request->query('image'));
        return response()->json(['image' => $storageImage]);
    }
}

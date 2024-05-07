<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{


    public function index(Request $request)
    {
        $data = [
            'message' => trans('main.title')
        ];
        return response()->json($data, 200);
    }
}

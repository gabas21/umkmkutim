<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $slides = Slider::where('status', 'active')->latest()->get();

        return response()->json([
            'data' => $slides,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LivraisonController extends Controller
{
    public function index(): View
    {
        return view('livraisons.index');
    }
}

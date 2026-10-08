<?php

namespace App\Http\Controllers;

class KlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('klant.index', [
            'title' => 'Klant Page'
        ]);
    }

}

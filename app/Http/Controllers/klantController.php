<?php

namespace App\Http\Controllers;

use App\Providers\klant\index\Index;
use Illuminate\View\View;

class KlantController extends Controller
{
    public function __construct(private Index $provider) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('klant.index', $this->provider->paginaData());
    }

}

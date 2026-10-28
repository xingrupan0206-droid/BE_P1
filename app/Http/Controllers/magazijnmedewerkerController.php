<?php

namespace App\Http\Controllers;

use App\Providers\magazijnmedewerker\index\Index;
use Illuminate\View\View;

class MagazijnmedewerkerController extends Controller
{
    public function __construct(private Index $provider) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('magazijnmedewerker.index', $this->provider->paginaData());
    }
}

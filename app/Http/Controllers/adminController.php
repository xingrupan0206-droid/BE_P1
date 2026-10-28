<?php

namespace App\Http\Controllers;

use App\Providers\admin\index\Index;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private Index $provider) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.index', $this->provider->paginaData());
    }

}

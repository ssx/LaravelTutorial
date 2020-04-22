<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->only([
                'index',
                'show'
            ]);
    }

    /**
     * @return View
     */
    public function index()
    {
        return view (
            'modules.index',
            ['modules' => Auth::user()->modules]
        );
    }

    /**
     * @param Module $module
     * @return View
     */
    public function show(Module $module)
    {
        Gate::authorize('view', $module);

        return view ('modules.show', compact('module'));
    }
}

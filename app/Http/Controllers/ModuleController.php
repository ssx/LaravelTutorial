<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Support\Facades\{Auth, Gate};
use \Illuminate\View\View;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'create',
            'store',
            'edit',
            'update',
            'destroy'
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

    public function show(Module $module)
    {
        Gate::authorize('view', $module);

        return view ('modules.show', compact('module'));
    }
}

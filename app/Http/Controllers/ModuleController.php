<?php

namespace App\Http\Controllers;

use App\Module;
use \Illuminate\View\View;

class ModuleController extends Controller
{

    /**
     * @return View
     */
    public function index()
    {
        return view (
            'modules.index',
            ['modules' => Module::all()]
        );
    }

    public function show(Module $module)
    {
        return view ('modules.show', compact('module'));
    }
}

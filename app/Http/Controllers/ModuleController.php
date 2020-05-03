<?php

namespace App\Http\Controllers;

use App\Module;
use App\Tutor;
use Illuminate\Support\Facades\{Auth, Gate};
use Illuminate\Http\{RedirectResponse, Request};
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
        $modules = Auth::user()->can('viewAny', Module::class)
            ? Module::all()
            : Auth::user()->modules;

        return view ('modules.index', compact('modules'));
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

    /**
     * @return View
     */
    public function create()
    {
        Gate::authorize('create', Module::class);

        $tutors = Tutor::all();
        return view ('modules.create', compact('tutors'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
       $validatedData = $request->validate([
           'id' => ['required', 'unique:App\Module', 'max:15'],
           'name' => ['required', 'max:50'],
           'lead_tutor_id' => ['required', 'exists:App\Tutor,id', 'max:5']
       ]);

       Module::create($validatedData);

       return redirect()->route('modules.index');
    }
}

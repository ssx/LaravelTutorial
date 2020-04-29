<?php

namespace App\Http\Controllers;

use App\Module;
use App\Tutor;
use Illuminate\Support\Facades\{Auth, Gate};
use Illuminate\Http\Request;
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
        if (Auth::user()->can('viewAny', Module::class))
        {
            $modules = Module::all();
        } else
        {
            $modules = Auth::user()->modules;

        }

        return view ('modules.index', compact('modules'));
    }

    public function show(Module $module)
    {
        Gate::authorize('view', $module);

        return view('modules.show', compact('module'));
    }

    public function create()
    {
        Gate::authorize('create', Module::class);

        $tutors = Tutor::all();

        return view ('modules.create', compact('tutors'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Module::class);

        $validatedData = $request->validate([
            'id' => ['required'],
            'name' => ['required'],
            'lead_tutor_id' => ['required'],
        ]);

        $module = new Module();
       $module->id = $validatedData['id'];
       $module->name = $validatedData['name'];
       $module->lead_tutor_id = $validatedData['lead_tutor_id'];
       $module->save();

       return redirect()->route('modules.index');
    }
}

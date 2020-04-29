<?php

namespace App\Http\Controllers;

use App\Module;
use App\Tutor;
use App\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ModuleTutorialController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Module  $module
     * @return View
     */
    public function create(Module $module)
    {
        Gate::authorize('create', Module::class);

        $tutors = Tutor::all();

        return view ('modules.tutorials.create', compact('module', 'tutors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Module  $module
     * @return RedirectResponse
     */
    public function store(Request $request, Module $module)
    {
        Gate::authorize('create', Module::class);

        $validatedData = $request->validate([
            'module_id' => ['required'],
            'time_start' => ['required'],
            'time_end' => ['required'],
            'room' => ['required'],
            'tutors' => ['required', function ($attribute, $value, $fail) {
                $tutors = Tutor::select('id')->get()->map(function ($t) {
                    return $t->id;
                });
                $failed = false;
                foreach ($value as $item) {
                    if (!$tutors->contains($item)) {
                        $fail("The selected tutors must exist in the system.");
                    }
                }
            }]
        ]);

        $tutorial = new Tutorial();
        $tutorial->module_id = $validatedData['module_id'];
        $tutorial->time_start = $validatedData['time_start'];
        $tutorial->time_end = $validatedData['time_end'];
        $tutorial->room = $validatedData['room'];

        $tutorial->save();

        $tutorial->tutors()->attach($request->get('tutors'));
        return redirect()->route('modules.show', $module->id);
    }
}

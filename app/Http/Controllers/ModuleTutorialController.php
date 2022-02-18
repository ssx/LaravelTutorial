<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Tutor;
use App\Models\Tutorial;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ModuleTutorialController extends Controller
{

    /**
     * ModuleTutorialController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Module $module
     * @return View
     */
    public function create(Module $module)
    {
        Gate::authorize('create', Module::class);

        $tutors = Tutor::all();
        return view('modules.tutorials.create', compact('module', 'tutors'));
    }

    /**
     * @param Request $request
     * @param Module $module
     * @return RedirectResponse
     */
    public function store(Request $request, Module $module)
    {
        $validatedTutorialData = $request->validate([
            'module_id' => ['required', 'exists:modules,id'],
            'time_start' => ['required', 'date'],
            'time_end' => ['required', 'date', 'after:time_start'],
            'room' => ['required', 'max:5']
        ]);
        $tutorial = Tutorial::create($validatedTutorialData);

        $validatedTutorIds = $request->validate([
            'tutors' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($value as $tutorId) {
                        if (!Tutor::where('id', $tutorId)->exists()) {
                            $fail('All tutors must exist on the system.');
                            break;
                        }
                    }
                }
            ]
        ]);

        $tutorial->tutors()->attach($validatedTutorIds['tutors']);
        return redirect()->route('modules.show', $module);
    }
}

<?php

namespace Tests\Unit;

use App\Module;
use App\Tutor;
use App\Tutorial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorialTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function tutors_method_returns_array_of_tutor_type()
    {
        $module = factory(Module::class)->create();
        $tutorial = factory(Tutorial::class)->create([
            'module_id' => $module->id
        ]);

        $tutorial->tutors()->attach(factory(Tutor::class, 3)->create());

        $this->assertTrue(get_class($tutorial->tutors->first()) == Tutor::class);
    }
}

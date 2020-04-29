<?php

namespace Tests\Feature;

use App\Module;
use App\Tutor;
use App\Tutorial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TimetableSetupTesting;

class ManageTutorialTest extends TestCase {

    use RefreshDatabase;
    use TimetableSetupTesting;

    /** @test */
    public function admin_can_see_link_to_create_a_tutorial()
    {
        $this->actingAs($this->createUserAs('admin'));
        $module = factory(Module::class)->create();
        $response = $this->get("modules/{$module->id}");

        $response->assertSee("/modules/{$module->id}/tutorials/create");
    }

    /** @test */
    public function ordinary_user_cannot_see_link_to_create_a_tutorial()
    {
        list($user, $module) = $this->setup_one_user_with_one_module();

        $response = $this->get("modules/{$module->id}");

        $response->assertDontSee("/modules/{$module->id}/tutorials/create");
    }

    /** @test */
    public function admin_sees_create_form_that_creates_tutorials()
    {
        $user = $this->createUserAs('admin');
        $module = factory(Module::class)->create();

        $response = $this->actingAs($user)->get("/modules/{$module->id}/tutorials/create");
        $response->assertSee('post');
        $response->assertSee("/modules/{$module->id}/tutorials");
        $response->assertSeeInOrder($this->idsAsArray(Tutor::all()));
    }

    /** @test */
    public function ordinary_user_cannot_see_create_form_that_creates_tutorials()
    {
        $user = $this->createUserAs('student');
        $module = factory(Module::class)->create();

        $response = $this->actingAs($user)->get("/modules/{$module->id}/tutorials/create");
        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_create_a_tutorial_in_database()
    {
        $module = factory(Module::class)->create();
        $tutorial = factory(Tutorial::class)->make(['module_id' => $module->id]);
        $tutors = factory(Tutor::class, 3)->create();
        $data = $tutorial->toArray();
        $data['tutors'] = $this->idsAsArray($tutors);


        $this->actingAs($this->createUserAs('admin'))
            ->post("/modules/{$module->id}/tutorials", $data);

        $this->assertDatabaseHas('tutorials', $tutorial->toArray());
        $this->assertDatabaseHas('tutor_tutorial', [
            'tutor_id' => $data['tutors'][0],
            'tutorial_id' => Tutorial::all()->first()->id
        ]);
    }

    /** @test */
    public function ordinary_user_cannot_create_a_tutorial()
    {
        $module = factory(Module::class)->create();
        $tutorial = factory(Tutorial::class)->make();

        $response = $this->actingAs($this->createUserAs('student'))
            ->post("/modules/{$module->id}/tutorials", $tutorial->toArray());

        $response->assertForbidden();
    }

    /** @test */
    public function validate_tutor_when_creating_tutorial_tutor_not_in_database()
    {
        $module = factory(Module::class)->create();
        $tutorial = factory(Tutorial::class)->make(['module_id' => $module->id]);
        $tutors = factory(Tutor::class, 3)->create();
        $data = $tutorial->toArray();

        $tutor = factory(Tutor::class)->make(); // tutor not in database
        $data['tutors'] = [$tutor->id];

        $response = $this->actingAs($this->createUserAs('admin'))
            ->post("/modules/{$module->id}/tutorials", $data);

        $response->assertSessionHasErrors('tutors');
    }
}

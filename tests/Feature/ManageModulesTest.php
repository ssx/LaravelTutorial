<?php

namespace Tests\Feature;

use App\{User, Module, Tutor, Tutorial};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageModulesTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function index_returns_list_of_modules_title()
    {
        $this->seed();
        $this->withoutExceptionHandling();

        $response = $this->actingAs(factory(User::class)->create())
            ->get('/modules');
        $response->assertSeeText('List of Modules');
    }

    /** @test */
    public function user_can_see_list_of_their_modules()
    {
        // Create the user
        $user = factory(User::class)->create();

        // Create the modules
        $modules = factory(Module::class, 3)->create();

        // Associate the modules with the user
        $user->modules()->attach($modules);

        $response = $this->actingAs($user)->get('/modules');
        $response->assertSeeInOrder($this->idsAsArray($modules));
    }

    /** @test */
    public function user_cannot_see_what_is_not_their_module_in_module_list()
    {
        $user = factory(User::class)->create();
        $module = factory(Module::class)->create();

        $response = $this->actingAs($user)->get('/modules');
        $response->assertDontSee($module->id);
    }

    /** @test */
    public function show_module_name_in_detail_view()
    {
        list($user, $module) = $this->setup_one_user_with_one_module();

        $response = $this->actingAs($user)
            ->get("modules/{$module->id}");

        $response->assertSeeText($module->name);
    }

    /** @test */
    public function show_module_leader_in_detail_view()
    {
        list($user, $module) = $this->setup_one_user_with_one_module();

        $response = $this->actingAs($user)
            ->get("modules/{$module->id}");

        $response->assertSeeText($module->leader->name);
    }

    /** @test */
    public function show_tutorials_information_in_detail_view()
    {
        // Alternative approach using seeding
        $this->seed();

        $user = User::find(1);
        $moduleId = $user->modules->last()->id;

        $response = $this->actingAs($user)
            ->get("modules/{$moduleId}");

        $response->assertSeeInOrder(['Friday', '09:00', '10:00', 'S507', 'Benhur Bakhtiari Bastaki']);
    }

    /** @test */
    public function show_unique_tutors_contact_details_in_detail_view()
    {
        $this->seed();

        $user = User::find(1);
        $moduleId = $user->modules->last()->id;

        $response = $this->actingAs($user)
            ->get("modules/{$moduleId}");

        $response->assertSeeTextInOrder([
            'Contact Details',
            'Benhur Bakhtiari Bastaki',
            'S341',
            'b.b.bastaki@staffs.ac.uk',
            'Kelvin Hilton',
            'S307',
            'k.c.hilton@staffs.ac.uk'
        ]);
    }

    /** @test */
    public function user_forbidden_response_on_detail_for_module_not_their_own()
    {
        $this->actingAs(factory(User::class)->create());

        $module = factory(Module::class)->create();

        $response = $this->get("/modules/{$module->id}");
        $response->assertForbidden();
    }

    /** @test */
    public function anonymous_user_cannot_view_module_list()
    {
        $response = $this->get('modules/');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function anonymous_user_cannot_view_module_detail()
    {
        $module = factory(Module::class)->create();

        $response = $this->get("modules/{$module->id}");
        $response->assertRedirect('/login');
    }

    /**
     * @param $values
     * @return array
     */
    private function idsAsArray($values)
    {
        return $values->map(
            function ($val) {
                return $val->id;
            }
        )->sort()->toArray();
    }

    private function setup_one_user_with_one_module(): array
    {
        $user = factory(User::class)->create();
        $module = factory(Module::class)->create();
        $user->modules()->attach($module);
        return array ($user, $module);
    }
}

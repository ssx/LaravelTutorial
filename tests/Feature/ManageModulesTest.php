<?php

namespace Tests\Feature;

use App\{Role, User, Module, Tutor, Tutorial};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TimetableSetupTesting;

class ManageModulesTest extends TestCase {

    use RefreshDatabase;
    use TimetableSetupTesting;

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
        $user = $this->createUserAs('student');

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
        $user = $this->createUserAs('student');
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
        $this->actingAs($this->createUserAs('student'));

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

    /** @test */
    public function admin_can_see_all_modules_in_list()
    {
        $user = $this->createUserAs('admin');
        $modules = factory(Module::class, 2)->create();

        $response = $this->actingAs($user)->get('modules');
        $response->assertSeeInOrder([
            $modules[0]->id,
            $modules[1]->id
        ]);
    }

    /** @test */
    public function admin_can_see_create_module_link()
    {
        $user = $this->createUserAs('admin');

        $response = $this->actingAs($user)->get('modules');
        $response->assertSee('/modules/create');
    }

    /** @test */
    public function ordinary_user_cannot_see_create_module_link()
    {
        $user = $this->createUserAs('student');

        $response = $this->actingAs($user)->get('modules');
        $response->assertDontSee('/modules/create');
    }

    /** @test */
    public function admin_sees_create_form_that_creates_modules()
    {
        $user = $this->createUserAs('admin');

        $response = $this->actingAs($user)->get('/modules/create');
        $response->assertSee('post');
        $response->assertSee('/modules');
        $response->assertSeeInOrder($this->idsAsArray(Tutor::all()));
    }

    /** @test */
    public function ordinary_user_cannot_see_create_form_that_creates_modules()
    {
        $user = $this->createUserAs('student');

        $response = $this->actingAs($user)->get('/modules/create');
        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_create_a_module_in_database()
    {
        $module = factory(Module::class)->make();

        $this->actingAs($this->createUserAs('admin'))
            ->post('/modules', $module->toArray());

        $this->assertDatabaseHas('modules', $module->toArray());
    }

    /** @test */
    public function ordinary_user_cannot_create_a_module()
    {
        $module = factory(Module::class)->make();

        $response = $this->actingAs($this->createUserAs('student'))
            ->post('/modules', $module->toArray());

        $response->assertForbidden();
    }
}

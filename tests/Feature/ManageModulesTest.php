<?php

namespace Tests\Feature;

use App\Module;
use App\Tutor;
use App\Tutorial;
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

        $response = $this->get('/modules');
        $response->assertSeeText('List of Modules');
    }

    /** @test */
    public function index_returns_list_of_modules_data()
    {
       $modules = factory(Module::class, 3)->create();

       $response = $this->get('/modules');
       $response->assertSeeInOrder($this->idsAsArray($modules));
    }

    /** @test */
    public function show_module_name_in_detail_view()
    {
        $this->withoutExceptionHandling();

        $module = factory(Module::class)
            ->create([
                'name' => 'Enterprise Cloud Computing'
            ]);

        $response = $this->get("modules/{$module->id}");
        $response->assertSeeText('Enterprise Cloud Computing');
    }

    /** @test */
    public function show_module_leader_in_detail_view()
    {
        $module = factory(Module::class)->create();

        $response = $this->get("modules/{$module->id}");
        $response->assertSeeText($module->leader->name);
    }

    /** @test */
    public function show_tutorials_information_in_detail_view()
    {
       $module = factory(Module::class)->create();
       $tutorials = factory(Tutorial::class)->create([
           'module_id' => $module->id,
           'time_start' => '2019-09-25 09:00:00',
           'time_end' => '2019-09-25 12:00:00',
           'room' => 'S205'
       ]);
       $tutorials->each(function ($t) {
           $t->tutors()->attach(factory(Tutor::class, 2)->create([
               'name' => 'Carolin Bauer'
           ]));
       });

       $response = $this->get("modules/{$module->id}");
       $response->assertSeeInOrder(['Wednesday', '09:00', '12:00', 'S205', 'Carolin Bauer | Carolin Bauer']);
    }

    /** @test */
    public function show_unique_tutors_contact_details_in_detail_view()
    {
        $tutors = factory(Tutor::class, 2)->create();

        $module = factory(Module::class)->create([
            'lead_tutor_id' => $tutors[0]->id
        ]);

        $tutorial = factory(Tutorial::class)->create([
            'module_id' => $module->id
        ]);
        $tutorial->tutors()->attach($tutors);

        $response = $this->get("modules/{$module->id}");
        $response->assertSeeTextInOrder([
            'Contact Details',
            $tutors[0]->name,
            $tutors[0]->room,
            $tutors[0]->email,
            $tutors[1]->name,
            $tutors[1]->room,
            $tutors[1]->email
        ]);
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
        )->toArray();
    }
}

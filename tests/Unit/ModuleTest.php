<?php

namespace Tests\Unit;

use App\{Module, Tutor, Tutorial};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function module_leader_returns_a_tutor_type()
    {
        $module = factory(Module::class)->make();
        $this->assertTrue(get_class($module->leader) == Tutor::class);
    }

    /** @test */
    public function tutorials_returns_collection_of_tutorial_type()
    {
        $module = factory(Module::class)->create();

        factory(Tutorial::class, 3)->create(['module_id' => $module->id]);

        $this->assertEquals(Tutorial::class, get_class($module->tutorials->first()));
    }

    /** @test */
    public function unique_tutor_list_returned_from_uniqueTutors()
    {
        $tutors = factory(Tutor::class, 3)->create();
        $module = factory(Module::class)->create([
            'lead_tutor_id' => $tutors[0]->id
        ]);
        $tutorials = factory(Tutorial::class, 2)->create([
            'module_id' => $module->id
        ]);

        foreach ($tutorials as $tutorial) {
            $tutorial->tutors()->attach($tutors);
        }

        $expectedIds = $this->sortedIdsAsArray($tutors);
        $actualIds = $this->sortedIdsAsArray($module->uniqueTutors);
        $this->assertEquals($expectedIds, $actualIds);
    }

    private function sortedIdsAsArray($collection)
    {
       return $collection->map(
           function ($t) {
               return $t->id;
           }
           )->sort()->values()->toArray();
    }
}

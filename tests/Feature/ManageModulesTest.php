<?php

namespace Tests\Feature;

use App\Module;
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

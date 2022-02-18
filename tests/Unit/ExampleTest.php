<?php

namespace Tests\Unit;

use App\Services\CalculateService;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_calculates_10_plus_4_correctly()
    {
        $calculateService = new CalculateService();

        $this->assertEquals($calculateService->addFour(10), 14);
    }
}

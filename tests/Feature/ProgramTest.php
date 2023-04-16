<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProgramTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    // Testing prgorams index
    public function testGetAllProgram()
    {
        $response = $this->get('/api/v1/programs');

        $response->assertStatus(200);
    }

}

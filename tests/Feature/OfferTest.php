<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OfferTest extends TestCase
{
    public function testList()
    {
        $response = $this->getJson('offers');

        $this->assertEquals(1, count($response->decodeResponseJson()));
        $response->assertStatus(200);
    }
}

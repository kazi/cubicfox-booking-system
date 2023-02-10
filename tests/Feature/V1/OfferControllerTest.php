<?php

namespace Feature\V1;

use App\Models\Offer;
use Database\Factories\OfferFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;


class OfferControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testOffersList()
    {
        OfferFactory::factoryForModel(Offer::class)
            ->count(5)
            ->create();

        $response = $this->get(route('offers.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'data' => [
                    [
                        'id',
                        'day',
                        'price',
                        'room'
                    ],
                ],
            ]
        );
    }
}

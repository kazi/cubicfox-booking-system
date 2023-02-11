<?php

namespace Unit\Filters\Api;

use App\Filters\Api\ApiFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class ApiFilterTest extends TestCase
{
    public function testTransform(): void
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('query')
            ->once()
            ->with('field_name')
            ->andReturn(
                [
                    'gte' => 100,
                    'lte' => 200
                ]
            );

        $filter = new ApiFilter();
        $result = $filter->transform(
            $request,
            [
                'field_name' => [
                    'operators' => ['gte', 'lte'],
                    'mapping' => 'field_name_mapping'
                ]
            ]
        );

        $this->assertInstanceOf(Collection::class, collect($result));
        $this->assertEquals(
            [
                ['field_name_mapping', '>=', 100],
                ['field_name_mapping', '<=', 200]
            ],
            $result
        );

        Mockery::close();
    }
}

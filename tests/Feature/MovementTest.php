<?php

namespace Tests\Feature;


use App\Models\Movement;
use App\Models\MovementType;
use App\Services\MovementTypeService;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MovementTypeTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_load_to_box()
    {
        $databaseSeeder = new DatabaseSeeder();
        $databaseSeeder->run();

        $data = $this->getDataMovement();

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(201)
            ->getContent();

        $this->assertDatabaseHas('movements', $data);
    }

    /** @test */
    function the_bill_100000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['bill_100000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_bill_50000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['bill_50000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_bill_20000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['bill_20000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_bill_10000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['bill_1000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_bill_5000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['bill_5000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_bill_2000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['bill_2000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_bill_1000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['bill_1000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_coin_1000_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['coin_1000'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_coin_500_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['coin_500'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_coin_200_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['coin_200'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_coin_100_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['coin_100'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }

    /** @test */
    function the_coin_50_must_be_integer()
    {

        $data = $this->getDataMovement();
        $data['coin_50'] = 5.5;

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(422);

        $this->assertEquals(0, Movement::count());
    }



    /**
     * @return array
     */
    function getDataMovement(): array
    {
        return [
            'bill_100000'   => 5,
            'bill_50000'    => 5,
            'bill_20000'    => 5,
            'bill_10000'    => 5,
            'bill_5000'     => 5,
            'bill_2000'     => 5,
            'bill_1000'     => 5,
            'coin_1000'     => 5,
            'coin_500'      => 5,
            'coin_200'      => 5,
            'coin_100'      => 5,
            'coin_50'       => 5,
        ];
    }
}

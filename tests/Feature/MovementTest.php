<?php

namespace Tests\Feature;


use App\Models\Movement;
use App\Models\MovementType;
use App\Services\MovementTypeService;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MovementTypeTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MovementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_load_to_box()
    {
        $this->seed(MovementTypeTableSeeder::class);

        $data = $this->getDataMovement();

        $this->json('post', 'api/v1/load-base-to-box', $data)
            ->assertStatus(201)
            ->getContent();

        $this->assertDatabaseHas('movements', $data);
    }

    /** @test */
    function it_unload_to_box()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();

        $response = $this->json('post', 'api/v1/unload-base-to-box')
            ->assertStatus(200)
            ->decodeResponseJson();

        $this->assertEquals(-5, $response['data']['bill_100000']);
        $this->assertEquals(-5, $response['data']['bill_50000']);
        $this->assertEquals(-5, $response['data']['bill_20000']);
        $this->assertEquals(-5, $response['data']['bill_10000']);
        $this->assertEquals(-5, $response['data']['bill_5000']);
        $this->assertEquals(-5, $response['data']['bill_2000']);
        $this->assertEquals(-5, $response['data']['bill_1000']);
        $this->assertEquals(-5, $response['data']['coin_1000']);
        $this->assertEquals(-5, $response['data']['coin_500']);
        $this->assertEquals(-5, $response['data']['coin_200']);
        $this->assertEquals(-5, $response['data']['coin_100']);
        $this->assertEquals(-5, $response['data']['coin_50']);
        $this->assertEquals(2, $response['data']['movement_type_id']);
    }

    /** @test */
    function it_unload_to_box_twice()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();

        $this->json('post', 'api/v1/unload-base-to-box')
            ->assertStatus(200)
            ->getContent();

        $response = $this->json('post', 'api/v1/unload-base-to-box')
            ->assertStatus(404)
            ->decodeResponseJson();

        $this->assertEquals('no money in the box.', $response['error']);
    }

    /** @test */
    function it_show_status_box()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();

        $response = $this->json('get', 'api/v1/get-status-box')
            ->assertStatus(200)
            ->decodeResponseJson();

        $this->assertEquals(5, $response['data']['bill_100000']);
        $this->assertEquals(5, $response['data']['bill_50000']);
        $this->assertEquals(5, $response['data']['bill_20000']);
        $this->assertEquals(5, $response['data']['bill_10000']);
        $this->assertEquals(5, $response['data']['bill_5000']);
        $this->assertEquals(5, $response['data']['bill_2000']);
        $this->assertEquals(5, $response['data']['bill_1000']);
        $this->assertEquals(5, $response['data']['coin_1000']);
        $this->assertEquals(5, $response['data']['coin_500']);
        $this->assertEquals(5, $response['data']['coin_200']);
        $this->assertEquals(5, $response['data']['coin_100']);
        $this->assertEquals(5, $response['data']['coin_50']);
    }

    /** @test */
    function it_show_event_logs()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();
        $this->createUnloadBoxDummy();

        $response = $this->json('get', 'api/v1/get-event-logs')
            ->assertStatus(200)
            ->decodeResponseJson();

        $this->assertEquals(5, $response['data'][0]['bill_100000']);
        $this->assertEquals(5, $response['data'][0]['bill_50000']);
        $this->assertEquals(5, $response['data'][0]['bill_20000']);
        $this->assertEquals(5, $response['data'][0]['bill_10000']);
        $this->assertEquals(5, $response['data'][0]['bill_5000']);
        $this->assertEquals(5, $response['data'][0]['bill_2000']);
        $this->assertEquals(5, $response['data'][0]['bill_1000']);
        $this->assertEquals(5, $response['data'][0]['coin_1000']);
        $this->assertEquals(5, $response['data'][0]['coin_500']);
        $this->assertEquals(5, $response['data'][0]['coin_200']);
        $this->assertEquals(5, $response['data'][0]['coin_100']);
        $this->assertEquals(5, $response['data'][0]['coin_50']);
        $this->assertEquals(1, $response['data'][0]['movement_type_id']);

        $this->assertEquals(-5, $response['data'][1]['bill_100000']);
        $this->assertEquals(-5, $response['data'][1]['bill_50000']);
        $this->assertEquals(-5, $response['data'][1]['bill_20000']);
        $this->assertEquals(-5, $response['data'][1]['bill_10000']);
        $this->assertEquals(-5, $response['data'][1]['bill_5000']);
        $this->assertEquals(-5, $response['data'][1]['bill_2000']);
        $this->assertEquals(-5, $response['data'][1]['bill_1000']);
        $this->assertEquals(-5, $response['data'][1]['coin_1000']);
        $this->assertEquals(-5, $response['data'][1]['coin_500']);
        $this->assertEquals(-5, $response['data'][1]['coin_200']);
        $this->assertEquals(-5, $response['data'][1]['coin_100']);
        $this->assertEquals(-5, $response['data'][1]['coin_50']);
        $this->assertEquals(2, $response['data'][1]['movement_type_id']);
    }

    /** @test */
    function it_show_custom_box_status()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();
        $this->createUnloadBoxDummy();

        $attributes = ['start_date' => '2021-05-15', 'final_date' => '2021-05-31'];

        $response = $this->json('get', 'api/v1/get-custom-box-status', $attributes)
            ->assertStatus(200)
            ->decodeResponseJson();

        $this->assertEquals(5, $response['data'][0]['bill_100000']);
        $this->assertEquals(5, $response['data'][0]['bill_50000']);
        $this->assertEquals(5, $response['data'][0]['bill_20000']);
        $this->assertEquals(5, $response['data'][0]['bill_10000']);
        $this->assertEquals(5, $response['data'][0]['bill_5000']);
        $this->assertEquals(5, $response['data'][0]['bill_2000']);
        $this->assertEquals(5, $response['data'][0]['bill_1000']);
        $this->assertEquals(5, $response['data'][0]['coin_1000']);
        $this->assertEquals(5, $response['data'][0]['coin_500']);
        $this->assertEquals(5, $response['data'][0]['coin_200']);
        $this->assertEquals(5, $response['data'][0]['coin_100']);
        $this->assertEquals(5, $response['data'][0]['coin_50']);
        $this->assertEquals(1, $response['data'][0]['movement_type_id']);

        $this->assertEquals(-5, $response['data'][1]['bill_100000']);
        $this->assertEquals(-5, $response['data'][1]['bill_50000']);
        $this->assertEquals(-5, $response['data'][1]['bill_20000']);
        $this->assertEquals(-5, $response['data'][1]['bill_10000']);
        $this->assertEquals(-5, $response['data'][1]['bill_5000']);
        $this->assertEquals(-5, $response['data'][1]['bill_2000']);
        $this->assertEquals(-5, $response['data'][1]['bill_1000']);
        $this->assertEquals(-5, $response['data'][1]['coin_1000']);
        $this->assertEquals(-5, $response['data'][1]['coin_500']);
        $this->assertEquals(-5, $response['data'][1]['coin_200']);
        $this->assertEquals(-5, $response['data'][1]['coin_100']);
        $this->assertEquals(-5, $response['data'][1]['coin_50']);
        $this->assertEquals(2, $response['data'][1]['movement_type_id']);
    }

    /** @test */
    function it_create_payment_made()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();

        $data = $this->getDataPayMovement();

        $response = $this->json('post', 'api/v1/make-payment', $data)
            ->assertStatus(201)
            ->decodeResponseJson();


        $this->assertEquals(-1, $response['data']['bill_10000']);
        $this->assertEquals(-1, $response['data']['bill_5000']);

    }

    /** @test */
    function it_amount_to_create_payment_is_required()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();

        $data = $this->getDataPayMovement();
        unset($data['amount']);

        $response = $this->json('post', 'api/v1/make-payment', $data)
            ->assertStatus(422)
            ->decodeResponseJson();

        $this->assertEquals('The amount field is required.', $response['error']['amount'][0]);

    }

    /** @test */
    function it_amount_to_pay_must_less_than_cash_received()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLoadBoxDummy();

        $data = $this->getDataPayMovement();
        $data['amount'] = 500000;

        $response = $this->json('post', 'api/v1/make-payment', $data)
            ->assertStatus(400)
            ->decodeResponseJson();

        $this->assertEquals('the amount to pay is greater than the cash received.', $response['error']);

    }

    /** @test */
    function it_validate_there_is_not_enough_money_to_return()
    {
        DB::table('movement_types')->truncate();
        $this->seed(MovementTypeTableSeeder::class);

        $this->createLittleLoadBoxDummy();

        $data = $this->getDataPayMovement();

        $response = $this->json('post', 'api/v1/make-payment', $data)
            ->assertStatus(404)
            ->decodeResponseJson();

        $this->assertEquals('no money in the box.', $response['error']);

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
     * create Load box dummy
     */
    function createLoadBoxDummy(){
        $data = $this->getDataMovement();
        $data['movement_type_id'] = MovementTypeService::LOAD_BOX;

        Movement::create($data);
    }

    /**
     * create little Load box dummy
     */
    function createLittleLoadBoxDummy(){
        $data = $this->getDataLittleLoadMovement();
        $data['movement_type_id'] = MovementTypeService::LOAD_BOX;

        Movement::create($data);
    }

    /**
     * create Unload box dummy
     */
    function createUnloadBoxDummy(){
        $data = $this->getDataUnloadMovement();
        $data['movement_type_id'] = MovementTypeService::UNLOAD_BOX;

        Movement::create($data);
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

    /**
     * @return array
     */
    function getDataUnloadMovement(): array
    {
        return [
            'bill_100000'   => -5,
            'bill_50000'    => -5,
            'bill_20000'    => -5,
            'bill_10000'    => -5,
            'bill_5000'     => -5,
            'bill_2000'     => -5,
            'bill_1000'     => -5,
            'coin_1000'     => -5,
            'coin_500'      => -5,
            'coin_200'      => -5,
            'coin_100'      => -5,
            'coin_50'       => -5,
        ];
    }

    /**
     * @return array
     */
    function getDataPayMovement(): array
    {
        // total efectivo 190.000
        return [
            'amount'        => 175000,
            'bill_100000'   => 0,
            'bill_50000'    => 3,
            'bill_20000'    => 2,
            'bill_10000'    => 0,
            'bill_5000'     => 0,
            'bill_2000'     => 0,
            'bill_1000'     => 0,
            'coin_1000'     => 0,
            'coin_500'      => 0,
            'coin_200'      => 0,
            'coin_100'      => 0,
            'coin_50'       => 0,
        ];
    }

    /**
     * @return array
     */
    function getDataLittleLoadMovement(): array
    {
        // total efectivo 8.000
        return [
            'bill_100000'   => 0,
            'bill_50000'    => 0,
            'bill_20000'    => 0,
            'bill_10000'    => 0,
            'bill_5000'     => 1,
            'bill_2000'     => 1,
            'bill_1000'     => 1,
            'coin_1000'     => 0,
            'coin_500'      => 0,
            'coin_200'      => 0,
            'coin_100'      => 0,
            'coin_50'       => 0,
        ];
    }
}

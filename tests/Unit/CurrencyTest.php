<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_convert_cny_to_usd()
    {
        $amount_cny = 100;
        $this->assertEquals(16, (new CurrencyService())->convert($amount_cny, 'cny', 'usd'));
    }

    public function test_convert_cny_to_gbp()
    {
        $amount_cny = 100;
        $this->assertEquals(0, (new CurrencyService())->convert($amount_cny, 'cny', 'gbp'));
    }

    public function test_convert_gbp_to_cny()
    {
        $amount_gbp = 100;
        $this->assertEquals(0, (new CurrencyService())->convert($amount_gbp, 'gbp', 'cny'));
    }
}

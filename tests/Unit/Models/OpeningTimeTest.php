<?php

namespace Tests\Unit\Models;

use App\Models\OpeningTime;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpeningTimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_for_day_scope(): void
    {
        $knownDate = Carbon::create(2020, 1, 1, 8, 30);
        Carbon::setTestNow($knownDate);
        $monday = factory(OpeningTime::class)->create([
            'day' => Carbon::MONDAY,
        ]);
        $tuesday = factory(OpeningTime::class)->create([
            'day' => Carbon::TUESDAY,
        ]);

        $result = OpeningTime::forDay('UTC', Carbon::MONDAY)->get();

        $ids = $result->map->id;
        $this->assertContains($monday->id, $ids);
        $this->assertNotContains($tuesday->id, $ids);
    }

    public function test_for_day_scope_defaults_to_today(): void
    {
        $today = factory(OpeningTime::class)->create([
            'day' => Carbon::now()->dayOfWeek,
        ]);
        $tomorrow = factory(OpeningTime::class)->create([
            'day' => Carbon::tomorrow()->dayOfWeek,
        ]);

        $result = OpeningTime::forDay('UTC')->get();

        $ids = $result->map->id;
        $this->assertContains($today->id, $ids);
        $this->assertNotContains($tomorrow->id, $ids);
    }

    public function test_is_not_open_when_before_opens_at(): void
    {
        $knownDate = Carbon::create(2020, 1, 1, 8, 30);
        Carbon::setTestNow($knownDate);
        $openingTime = new OpeningTime([
            'opens_at' => '09:00',
            'closes_at' => '17:00',
        ]);

        $this->assertFalse($openingTime->isOpen());
    }

    public function test_is_not_open_when_after_closes_at(): void
    {
        $knownDate = Carbon::create(2020, 1, 1, 17, 30);
        Carbon::setTestNow($knownDate);
        $openingTime = new OpeningTime([
            'opens_at' => '09:00',
            'closes_at' => '17:00',
        ]);

        $this->assertFalse($openingTime->isOpen());
    }

    public function test_is_open(): void
    {
        $knownDate = Carbon::create(2020, 1, 1, 17, 0);
        Carbon::setTestNow($knownDate);
        $openingTime = new OpeningTime([
            'opens_at' => '09:00',
            'closes_at' => '17:00',
        ]);

        $this->assertTrue($openingTime->isOpen());
    }

    public function test_is_not_open_when_closes_at_before_opens_at(): void
    {
        $knownDate = Carbon::create(2020, 1, 1, 16, 0);
        Carbon::setTestNow($knownDate);
        $openingTime = new OpeningTime([
            'opens_at' => '17:00',
            'closes_at' => '09:00',
        ]);

        $this->assertFalse($openingTime->isOpen());
    }

    // New zealand is 12 hours ahead
    public function test_closed_in_new_zealand_timezone(): void
    {
        $knownDate = Carbon::create(2020, 1, 1, 9, 0);
        Carbon::setTestNow($knownDate);
        $openingTime = new OpeningTime([
            'opens_at' => '09:00',
            'closes_at' => '17:00',
        ]);
        $timezone = 'Pacific/Auckland';

        $this->assertFalse($openingTime->isOpen($timezone));
    }

    // New zealand is 12 hours ahead
    public function test_open_in_new_zealand_timezone(): void
    {
        $knownDate = Carbon::create(2020, 1, 1, 2, 0);
        Carbon::setTestNow($knownDate);
        $openingTime = new OpeningTime([
            'opens_at' => '09:00',
            'closes_at' => '17:00',
        ]);
        $timezone = 'Pacific/Auckland';

        $this->assertTrue($openingTime->isOpen($timezone));
    }

    public function test_opening_hours(): void
    {
        $openingTime = new OpeningTime([
            'opens_at' => '09:00',
            'closes_at' => '17:00',
        ]);

        $this->assertEquals('9:00AM - 17:00PM', $openingTime->openingHours());
    }

    public function testDayName(): void
    {
        $time = factory(OpeningTime::class)->make([
            'day' => Carbon::TUESDAY,
        ]);

        $dayName = Carbon::getDays()[Carbon::TUESDAY];
        $this->assertEquals($dayName, $time->dayName());
    }
}

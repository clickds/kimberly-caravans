<?php

namespace Tests\Feature\Admin\Vacancies;

use App\Models\Dealer;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $this->createDefaultSite();

        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.vacancies.index'));

        $this->assertDatabaseHas('vacancies', Arr::except($data, ['dealer_ids']));
    }

    public function test_creates_page_for_default_site()
    {
        $defaultSite = $this->createDefaultSite();

        $data = $this->validFields();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.vacancies.index'));

        $vacancy = Vacancy::firstOrFail();

        $this->assertDatabaseHas('pages', [
            'site_id' => $defaultSite->id,
            'pageable_type' => Vacancy::class,
            'pageable_id' => $vacancy->id,
        ]);
    }

    /**
     * @dataProvider requiredFieldsProvider
     * @param $requiredField string
     */
    public function test_required_validation(string $requiredField): void
    {
        $this->createDefaultSite();

        $data = $this->validFields([$requiredField => null]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($requiredField);
    }

    public function requiredFieldsProvider(): array
    {
        return [
            ['title'],
            ['short_description'],
            ['description'],
            ['dealer_ids'],
            ['published_at'],
        ];
    }

    private function createDealers(): Collection
    {
        return factory(Dealer::class, 3)->create();
    }

    private function submit($data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url();

        return $this->actingAs($admin)->post($url, $data);
    }

    private function validFields($overrides = []): array
    {
        $dealers = $this->createDealers();

        $defaults = [
            'title' => $this->faker->jobTitle,
            'salary' => '£10,000 per anum',
            'short_description' => $this->faker->paragraph,
            'description' => $this->faker->paragraph,
            'dealer_ids' => $dealers->pluck('id')->toArray(),
            'published_at' => Carbon::now(),
            'expired_at' => Carbon::now()->addMonth()
        ];

        return array_merge($defaults, $overrides);
    }

    private function url(): string
    {
        return route('admin.vacancies.store');
    }
}

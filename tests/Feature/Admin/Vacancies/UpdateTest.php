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

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful()
    {
        $this->createDefaultSite();

        $vacancy = $this->createVacancy();

        $data = $this->validFields();

        $response = $this->submit($vacancy, $data);

        $response->assertRedirect(route('admin.vacancies.index'));

        $this->assertDatabaseHas('vacancies', Arr::except($data, ['dealer_ids']));
    }

    public function test_replaces_page_for_default_site()
    {
        $defaultSite = $this->createDefaultSite();

        $originalVacancy = $this->createVacancy();

        $this->createPageForPageable($originalVacancy, $defaultSite);

        $data = $this->validFields();

        $response = $this->submit($originalVacancy, $data);

        $response->assertRedirect(route('admin.vacancies.index'));

        $updateVacancy = Vacancy::firstOrFail();

        $this->assertDatabaseHas('pages', [
            'site_id' => $defaultSite->id,
            'pageable_type' => Vacancy::class,
            'pageable_id' => $updateVacancy->id,
            'name' => $updateVacancy->title,
        ]);

        $this->assertDatabaseMissing('pages', [
            'site_id' => $defaultSite->id,
            'pageable_type' => Vacancy::class,
            'pageable_id' => $originalVacancy->id,
            'name' => $originalVacancy->title,
        ]);
    }

    /**
     * @dataProvider requiredFieldsProvider
     * @param $requiredField string
     */
    public function test_required_validation(string $requiredField): void
    {
        $vacancy = $this->createVacancy();

        $data = $this->validFields([$requiredField => null]);

        $response = $this->submit($vacancy, $data);

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

    private function createVacancy(): Vacancy
    {
        return factory(Vacancy::class)->create();
    }

    private function createDealers(): Collection
    {
        return factory(Dealer::class, 3)->create();
    }

    private function submit(Vacancy $vacancy, $data = []): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($vacancy);

        return $this->actingAs($admin)->put($url, $data);
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

    private function url(Vacancy $vacancy): string
    {
        return route('admin.vacancies.update', ['vacancy' => $vacancy]);
    }
}

<?php

namespace Tests\Feature\Admin\Vacancies;

use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful()
    {
        $this->createDefaultSite();

        $vacancy = $this->createVacancy();

        $response = $this->submit($vacancy);

        $response->assertRedirect(route('admin.vacancies.index'));

        $this->assertDatabaseMissing('vacancies', [
            'id' => $vacancy->id,
        ]);
    }

    public function test_removes_site_pages()
    {
        $defaultSite = $this->createDefaultSite();

        $vacancy = $this->createVacancy();

        $page = $this->createPageForPageable($vacancy, $defaultSite);

        $response = $this->submit($vacancy);

        $response->assertRedirect(route('admin.vacancies.index'));

        $this->assertDatabaseMissing('vacancies', [
            'id' => $vacancy->id,
        ]);

        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    private function createVacancy(): Vacancy
    {
        return factory(Vacancy::class)->create();
    }

    private function submit(Vacancy $vacancy): TestResponse
    {
        $admin = $this->createSuperUser();

        $url = $this->url($vacancy);

        return $this->actingAs($admin)->delete($url);
    }

    private function url(Vacancy $vacancy): string
    {
        return route('admin.vacancies.destroy', $vacancy);
    }
}

<?php

namespace Tests\Unit\Services\Site;

use App\Models\Vacancy;
use App\Services\Site\VacancyApplicationSaver;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VacancyApplicationSaverTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_saves_application_for_vacancy()
    {
        $vacancy = $this->createVacancy();

        $applicationData = $this->getValidApplicationData();

        $vacancyApplicationSaver = new VacancyApplicationSaver();

        $vacancyApplicationSaver->save($vacancy, $applicationData);

        $this->assertDatabaseHas('vacancy_applications', Arr::except($applicationData, 'employment_history'));

        foreach ($applicationData['employment_history'] as $previousEmployment) {
            $this->assertDatabaseHas('vacancy_application_employment_history', $previousEmployment);
        }
    }

    private function createVacancy(): Vacancy
    {
        return factory(Vacancy::class)->create();
    }

    private function getValidApplicationData(): array
    {
        return [
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->address,
            'nationality' => $this->faker->country,
            'require_permission_to_work_in_uk' => $this->faker->boolean,
            'number_of_dependents' => $this->faker->randomDigit,
            'telephone_number' => $this->faker->phoneNumber,
            'mobile_number' => $this->faker->phoneNumber,
            'have_own_transport' => $this->faker->boolean,
            'currently_employed_by' => $this->faker->company,
            'current_position' => $this->faker->jobTitle,
            'seeking_employment_change_reason' => $this->faker->paragraph,
            'weeks_notice_required' => $this->faker->randomDigit,
            'conviction_details' => $this->faker->paragraph,
            'marquis_employee_reference_name' => $this->faker->firstName . $this->faker->lastName,
            'booked_holiday_details' => $this->faker->paragraph,
            'have_any_disabilities' => $this->faker->boolean,
            'disability_details' => $this->faker->boolean,
            'wear_glasses_or_contacts' => $this->faker->boolean,
            'glasses_or_contacts_details' => $this->faker->paragraph,
            'receiving_medical_treatment' => $this->faker->boolean,
            'medical_treatment_details' => $this->faker->paragraph,
            'smoker' => $this->faker->boolean,
            'courses_and_certificates' => $this->faker->paragraph,
            'practical_experience' => $this->faker->paragraph,
            'hobbies_and_interests' => $this->faker->paragraph,
            'references' => $this->faker->paragraph,
            'employment_history' => [
                $this->getValidEmploymentHistoryData(),
                $this->getValidEmploymentHistoryData(),
            ],
        ];
    }

    private function getValidEmploymentHistoryData(): array
    {
        return [
            'position' => $this->faker->jobTitle,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'employer_details' => $this->faker->paragraph,
            'reasons_for_leaving' => $this->faker->paragraph,
        ];
    }
}

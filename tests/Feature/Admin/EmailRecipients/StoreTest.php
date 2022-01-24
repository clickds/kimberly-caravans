<?php

namespace Tests\Feature\Admin\EmailRecipients;

use App\Models\EmailRecipient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['name'],
            ['email'],
            ['receives_vehicle_enquiry'],
        ];
    }

    public function test_email_validation(): void
    {
        $data = $this->validData([
            'email' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('email');
    }

    public function test_unique_validation(): void
    {
        $otherEmailRecipient = factory(EmailRecipient::class)->create();
        $data = $this->validData([
            'email' => $otherEmailRecipient->email,
        ]);

        $response = $this->submit($data);

        $response->assertSessionHasErrors('email');
    }

    public function test_successfully_creates_recipient(): void
    {
        $data = $this->validData();

        $response = $this->submit($data);

        $response->assertRedirect(route('admin.email-recipients.index'));
        $this->assertDatabaseHas('email_recipients', $data);
    }

    private function submit(array $data): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.email-recipients.store');

        return $this->actingAs($user)->post($url, $data);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'receives_vehicle_enquiry' => true,
        ];

        return array_merge($defaults, $overrides);
    }
}

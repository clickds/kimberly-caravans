<?php

namespace Tests\Feature\Admin\EmailRecipients;

use App\Models\EmailRecipient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_deletes_recipient(): void
    {
        $recipient = factory(EmailRecipient::class)->create();

        $response = $this->submit($recipient);

        $response->assertRedirect(route('admin.email-recipients.index'));
        $this->assertDatabaseMissing('email_recipients', $recipient->getAttributes());
    }

    private function submit(EmailRecipient $emailRecipient): TestResponse
    {
        $user = $this->createSuperUser();
        $url = route('admin.email-recipients.destroy', $emailRecipient);

        return $this->actingAs($user)->delete($url);
    }
}

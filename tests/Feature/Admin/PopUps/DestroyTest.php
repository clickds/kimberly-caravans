<?php

namespace Tests\Feature\Admin\PopUps;

use App\Models\PopUp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroying_popup()
    {
        $popUp = factory(PopUp::class)->create();

        $response = $this->submit($popUp);

        $response->assertRedirect(route('admin.pop-ups.index'));
        $this->assertDatabaseMissing('pop_ups', $popUp->getAttributes());
    }

    private function submit(PopUp $popUp)
    {
        $user = $this->createSuperUser();
        $url = route('admin.pop-ups.destroy', $popUp);

        return $this->actingAs($user)->delete($url);
    }
}

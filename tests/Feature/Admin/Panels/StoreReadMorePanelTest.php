<?php

namespace Tests\Feature\Admin\Panels;

use App\Models\Panel;

class StoreReadMorePanelTest extends BaseStorePanelTestCase
{
    /**
     * @dataProvider required_fields_provider
     */
    public function test_required_fields(string $requiredFieldName): void
    {
        $data = $this->valid_fields([$requiredFieldName => null]);
        $response = $this->submit($data);
        $response->assertSessionHasErrors($requiredFieldName);
    }

    public function required_fields_provider(): array
    {
        return array_merge(
            $this->default_required_fields(),
            [
                ['content'],
                ['read_more_content'],
            ]
        );
    }

    public function test_successful(): void
    {
        $data = $this->valid_fields();
        $response = $this->submit($data);
        $response->assertRedirect($this->redirect_url());
        $this->assertDatabaseHas('panels', $data);
    }

    protected function valid_fields(array $overrides = []): array
    {
        return array_merge(
            $this->default_valid_fields(),
            [
                'type' => Panel::TYPE_READ_MORE,
                'content' => '<h1>Some content</h1>',
                'read_more_content' => '<h2>Some read more content</h2>',
            ],
            $overrides
        );
    }
}

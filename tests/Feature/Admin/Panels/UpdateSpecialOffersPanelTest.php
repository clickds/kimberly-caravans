<?php

namespace Tests\Feature\Admin\Panels;

use App\Models\Panel;
use App\Models\SpecialOffer;
use Illuminate\Support\Arr;

class UpdateSpecialOffersPanelTest extends BaseUpdatePanelTestCase
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
                ['special_offer_ids'],
            ]
        );
    }

    public function test_it_requires_special_offers_to_exist()
    {
        $data = $this->valid_fields([
            'special_offer_ids' => [0],
        ]);

        $response = $this->submit($data);
        $response->assertSessionHasErrors('special_offer_ids.0');
    }

    public function test_successful(): void
    {
        $data = $this->valid_fields();
        $response = $this->submit($data);
        $response->assertRedirect($this->redirect_url());
        $this->assertDatabaseHas('panels', Arr::except($data, ['special_offer_ids']));

        $specialOfferIds = Arr::only($data, ['special_offer_ids']);

        foreach ($specialOfferIds as $specialOfferId) {
            $this->assertDatabaseHas('panel_special_offer', [
                'panel_id' => $this->panel->id,
                'special_offer_id' => $specialOfferId,
            ]);
        }
    }

    protected function valid_fields(array $overrides = []): array
    {
        $validFields = array_merge(
            $this->default_valid_fields(),
            ['type' => Panel::TYPE_SPECIAL_OFFERS],
            $overrides
        );

        if (!array_key_exists('special_offer_ids', $overrides)) {
            $special_offer = factory(SpecialOffer::class)->create();
            $validFields['special_offer_ids'] = [$special_offer->id];
        }
        return $validFields;
    }
}

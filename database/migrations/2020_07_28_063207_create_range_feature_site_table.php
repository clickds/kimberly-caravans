<?php

use App\Models\RangeFeature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRangeFeatureSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_feature_site', function (Blueprint $table) {
            $table->foreignId('range_feature_id');
            $table->foreignId('site_id');

            $table->unique(['site_id', 'range_feature_id']);

            $table->foreign('site_id')->references('id')->on('sites')->cascadeOnDelete();
            $table->foreign('range_feature_id')->references('id')->on('range_features')->cascadeOnDelete();
        });
        RangeFeature::chunk(200, function ($rangeFeatures) {
            foreach ($rangeFeatures as $rangeFeature) {
                $rangeFeature->sites()->sync($rangeFeature->site_id);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('range_feature_site', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropForeign(['range_feature_id']);
        });
        Schema::dropIfExists('range_feature_site');
        RangeFeature::chunk(200, function ($rangeFeatures) {
            foreach ($rangeFeatures as $rangeFeature) {
                $site = $rangeFeature->sites()->first();
                if ($site) {
                    $rangeFeature->site_id = $site->id;
                    $rangeFeature->save();
                }
            }
        });
    }
}

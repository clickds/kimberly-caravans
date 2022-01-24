<?php

use App\Models\Motorhome;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBerthsFromMotorhomes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->dropColumn('berths');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorhomes', function (Blueprint $table) {
            $table->unsignedInteger('berths')->index()->default(2);
        });
        Motorhome::chunk(200, function ($items) {
            foreach ($items as $item) {
                $item->berths = $item->berths()->pluck('number')->first();
                $item->save();
            }
        });
    }
}

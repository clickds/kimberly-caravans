<?php

use App\Models\Caravan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBerthsFromCaravans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caravans', function (Blueprint $table) {
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
        Schema::table('caravans', function (Blueprint $table) {
            $table->unsignedInteger('berths')->index()->default(2);
        });
        Caravan::chunk(200, function ($items) {
            foreach ($items as $item) {
                $item->berths = $item->berths()->pluck('number')->first();
                $item->save();
            }
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\siteSetting;

class SeedSiteSettingsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('site_setting');

        siteSetting::create(['site_name' => 'laraBBS']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        app()['cache']->forget('site_setting');
        \DB::table('site_settings')->delete();
    }
}

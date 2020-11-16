<?php

use App\FooterSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFooterSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('footer_settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('slug');
			$table->longText('description')->nullable()->default(null);
			$table->enum('status', ['active', 'inactive'])->nullable()->default('active');
			$table->timestamps();
		});

		$customMenu = new FooterSetting();
		$customMenu->name = 'About';
		$customMenu->slug = 'about';
		$customMenu->description = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.";
		$customMenu->save();

		$customMenu = new FooterSetting();
		$customMenu->name = 'Help';
		$customMenu->slug = 'help';
		$customMenu->description = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.";
		$customMenu->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('footer_settings');
	}
}

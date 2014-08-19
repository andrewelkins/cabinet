<?php

use Illuminate\Database\Migrations\Migration;

class CabinetSetupUploadsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the uploads table
        Schema::create('uploads', function($table)
        {
            $table->increments('id');
            $table->string('filename');
            $table->string('path');
            $table->string('extension');
            $table->string('mimetype');
            $table->bigInteger('size')->unsigned();
            $table->integer('user_id')->unsigned()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('uploads');
    }

}
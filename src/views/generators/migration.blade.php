{{ '<?php' }}

use Illuminate\Database\Migrations\Migration;

class ConfideSetupUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the {{ $table }} table
        Schema::create('{{ $table }}', function($table)
        {
            $table->increments('id');
            $table->string('filename');
            $table->string('filetype');
            $table->integer('user_id')->unsigned()->index();
            $table->timestamp('deleted_at');
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
        Schema::drop('{{ $table }}');
    }

}

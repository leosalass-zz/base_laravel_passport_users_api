<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'users';

    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('id_state')->nullable();
            $table->string('full_name');
            $table->string('username', 45);
            $table->string('image', 100)->nullable();
            $table->string('email', 45);
            $table->string('password', 45);
            $table->integer('max_roles')->nullable()->default('1');
            $table->rememberToken();
            $table->string('last_login_ip', 45)->nullable();
            $table->dateTime('last_login')->nullable();

            $table->index(["id_state"], 'fk_users_user_states1_idx');

            $table->unique(["email"], 'email_UNIQUE');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('id_state', 'fk_users_user_states1_idx')
                ->references('id')->on('user_states')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->set_schema_table);
     }
}

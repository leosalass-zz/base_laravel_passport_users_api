<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasPermissionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'user_has_permissions';

    /**
     * Run the migrations.
     * @table user_has_permissions
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = '';
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_permission');

            $table->index(["id_permission"], 'fk_user_has_permissions_user_permissions1_idx');

            $table->index(["id_user"], 'fk_user_has_permissions_users1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('id_user', 'fk_user_has_permissions_users1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_permission', 'fk_user_has_permissions_user_permissions1_idx')
                ->references('id')->on('user_permissions')
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

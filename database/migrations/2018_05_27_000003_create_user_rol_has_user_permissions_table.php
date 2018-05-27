<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolHasUserPermissionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'user_rol_has_user_permissions';

    /**
     * Run the migrations.
     * @table user_rol_has_user_permissions
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = '';
            $table->increments('id');
            $table->integer('id_rol');
            $table->integer('id_permission');

            $table->index(["id_rol"], 'fk_rol_has_permissions_user_roles1_idx');

            $table->index(["id_permission"], 'fk_rol_has_permissions_user_permissions1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('id_rol', 'fk_rol_has_permissions_user_roles1_idx')
                ->references('id')->on('user_roles')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_permission', 'fk_rol_has_permissions_user_permissions1_idx')
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

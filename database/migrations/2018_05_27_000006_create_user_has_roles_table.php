<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasRolesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'user_has_roles';

    /**
     * Run the migrations.
     * @table user_has_roles
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_role');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table($this->set_schema_table, function (Blueprint $table){;

          $table->index(["id_role"], 'fk_user_has_roles_user_roles1_idx');
          $table->index(["id_user"], 'fk_user_has_roles_users1_idx');
          $table->unique(["updated_at"], 'updated_at_UNIQUE');
          $table->unique(["created_at"], 'created_at_UNIQUE');

          $table->foreign('id_user', 'fk_user_has_roles_users1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

          $table->foreign('id_role', 'fk_user_has_roles_user_roles1_idx')
                ->references('id')->on('user_roles')
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

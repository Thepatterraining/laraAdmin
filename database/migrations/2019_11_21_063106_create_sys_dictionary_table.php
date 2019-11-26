<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysDictionaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_dictionary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->comment('字典编码');
            $table->string('name')->comment('字典名称');
            $table->string('type')->comment('字典类型');
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
        Schema::dropIfExists('sys_dictionary');
    }
}

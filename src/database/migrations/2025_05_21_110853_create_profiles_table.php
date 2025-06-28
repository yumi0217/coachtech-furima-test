<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nickname')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 10)->nullable();

            // ▼ 追加カラム
            $table->string('username')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('building')->nullable();
            $table->string('profile_image')->nullable();

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
        Schema::dropIfExists('profiles');
    }
}

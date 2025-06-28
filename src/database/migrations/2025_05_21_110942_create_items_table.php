<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('brand_name')->nullable();           // ★ ブランド名（任意）
            $table->text('description');
            $table->integer('price');
            $table->string('condition');
            $table->string('color')->nullable();                // 色（任意）
            $table->boolean('is_new')->default(false);          // 新品かどうか
            $table->string('shipping_comment')->nullable();     // 発送コメント
            $table->string('image_url')->nullable();
            $table->boolean('is_sold')->default(false);         // 売り切れフラグ
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}

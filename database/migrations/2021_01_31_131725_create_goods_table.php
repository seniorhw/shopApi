<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('标题');
            $table->integer('user_id')->comment('创建者');
            $table->integer('category_id')->comment('分类id');
            $table->string('description')->comment('描述');
            $table->integer('price')->comment('价格');
            $table->integer('stock')->comment('库存');
            $table->string('cover')->comment('封面');
            $table->json('pics')->comment('小图集');
            $table->tinyInteger('is_on')->default(0)->comment('是否上架 0不上架');
            $table->tinyInteger('is_recommend')->default(0)->comment('是否推荐 0不推荐');
            $table->text('details')->comment('详情');
            $table->index('title');
            $table->index('category_id');
            $table->index('is_on');
            $table->index('is_recommend');
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
        Schema::dropIfExists('goods');
    }
}

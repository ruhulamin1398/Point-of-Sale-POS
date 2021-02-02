<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // weight->kg  
        //piece->piece  
        // warrenty->day
        // always store data in this table with only those units

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('type_id')->default(1);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('tax_type_id')->default(1);
            $table->unsignedBigInteger('warrenty_id')->default(1);
            

            $table->double('price_per_unit',18,2)->nullable();
            $table->double('cost_per_unit',18,2)->nullable();
            $table->integer('is_fixed_price')->default(1);
            $table->double('stock',18,6)->default(0);
            $table->double('stock_alert',18,6)->default(1);

            $table->double('sell',18,2)->default(0);
            $table->double('tax',18,2)->default(0);
            
            $table->string('stock_controll')->default('yes');

            
            $table->longText('description')->nullable();
             //save in day 
            $serial= [
                'status'=>false,
                'data'=>[
                    'a3bc'=> '2020:10:10',   //serial: 'date'
                ]
            ];
            $discount= [
                'status'=>false,
                'type'=>'% / amount',
                'value'=>'0',
            ];
            $table->json('serial')->default(json_encode($serial)); 
            $table->json('discount')->default(json_encode($discount));
            $table->json('data')->default(json_encode(['']));
            $table->softDeletes();
            $table->timestamps();
            
        });


        // category image brand dropdown view and update
        // price_per_unit , cost_per_unit , stock_alert , description , show and editable 
        // stock only showable 
        // warrenty only updateable

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

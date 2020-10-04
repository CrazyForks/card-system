<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class CreateOrdersTable extends Migration { public function up() { Schema::create('orders', function (Blueprint $sp07e895) { $sp07e895->increments('id'); $sp07e895->integer('user_id')->index(); $sp07e895->string('order_no', 128)->index(); $sp07e895->integer('product_id'); $sp07e895->string('product_name')->nullable(); $sp07e895->integer('count'); $sp07e895->string('ip')->nullable(); $sp07e895->string('customer', 32)->nullable(); $sp07e895->string('contact')->nullable(); $sp07e895->text('contact_ext')->nullable(); $sp07e895->tinyInteger('send_status')->default(App\Order::SEND_STATUS_UN); $sp07e895->text('remark')->nullable(); $sp07e895->integer('cost')->default(0); $sp07e895->integer('price')->default(0); $sp07e895->integer('discount')->default(0); $sp07e895->integer('paid')->default(0); $sp07e895->integer('fee')->default(0); $sp07e895->integer('system_fee')->default(0); $sp07e895->integer('income')->default(0); $sp07e895->integer('pay_id'); $sp07e895->string('pay_trade_no')->nullable(); $sp07e895->integer('status')->default(\App\Order::STATUS_UNPAY); $sp07e895->string('frozen_reason')->nullable(); $sp07e895->string('api_out_no', 128)->nullable(); $sp07e895->text('api_info')->nullable(); $sp07e895->dateTime('paid_at')->nullable(); $sp07e895->timestamps(); }); } public function down() { Schema::dropIfExists('orders'); } }
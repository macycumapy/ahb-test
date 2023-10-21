<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nomenclatures', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->comment('Код')->unique();
            $table->string('name')->comment('Наименование');
            $table->foreignId('nomenclature_type_id')->nullable()->comment('Тип номенклатуры')->constrained('nomenclature_types');
            $table->float('price')->default(0.0)->comment('Цена');
            $table->float('price_sp')->default(0.0)->comment('Цена СП');
            $table->float('quantity', 8, 3)->comment('Количество');
            $table->string('properties', 500)->comment('Поля свойств')->nullable();
            $table->boolean('joint_purchases')->default(false)->comment('Совместные покупки');
            $table->foreignId('measurement_id')->comment('Единица измерения')->constrained('measurements');
            $table->string('image_path')->nullable()->comment('Картинка');
            $table->boolean('show_main')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomenclatures');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('nit')->comment('Numero de identificacion Tributario')->unique();
            $table->string('name', 100)->comment('Nombre de la empresa')->nullable();
            $table->string('address', 100)->comment('Direccion de la empresa')->nullable();
            $table->string('phone')->comment('Telefono de contacto')->nullable();
            $table->boolean('active')->comment('Estado de actividad')->default(true);

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

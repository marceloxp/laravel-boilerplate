<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_type_id')->unsigned()->default(1)->comment('Tipo de Endereço');
            $table->string('name', 128)->comment('Nome');
            $table->string('username', 128)->comment('Usuário');
            $table->string('born', 10)->comment('Nascimento');
            $table->string('cpf', 24)->comment('CPF');
            $table->string('email', 128)->comment('E-Mail');
            $table->string('phone_prefix', 3)->comment('DDD');
            $table->string('phone', 24)->comment('Telefone');
            $table->string('cep', 10)->comment('CEP');
            $table->enum('state', ['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RO','RS','RR','SC','SE','SP','TO'])->comment('Estado');
            $table->string('city', 128)->comment('Cidade');
            $table->string('address', 256)->comment('Endereço');
            $table->string('address_number', 64)->nullable()->comment('Número');
            $table->string('complement', 128)->nullable()->comment('Complemento');
            $table->string('neighborhood', 128)->comment('Bairro');
            $table->string('password', 128)->comment('Senha');
            $table->boolean('newsletter')->default(false)->comment('Newsletter');
            $table->boolean('rules')->default(false)->comment('Regras');
            $table->enum('status', ['Ativo','Inativo'])->default('Ativo')->comment('Status');
            $table->string('ip', 64)->nullable()->comment('IP');
            $table->integer('flags')->nullable()->default(0)->comment('Flags');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['username']);
            $table->index(['status']);
            $table->index(['newsletter']);
            $table->index(['state']);
            $table->index(['rules']);
            $table->index(['deleted_at']);
            $table->unique(['cpf']);
            $table->unique(['email']);

            $table->foreign('address_type_id')->references('id')->on('address_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}

<?php

use App\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenTable extends Migration
{
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('divorce_cases')->onDelete('cascade');
            $table->string('first_name');
            $table->string('nationality_no')->nullable()->unique();
            $table->date('date_of_birth');
            $table->tinyInteger('gender');
            $table->string('birth_certificate_url');
            $table->tinyInteger('status')->default(StatusEnum::Active->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('children');
    }
}

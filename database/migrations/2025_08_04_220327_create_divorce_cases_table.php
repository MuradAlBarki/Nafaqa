<?php

use App\StatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivorceCasesTable extends Migration
{
    public function up()
    {
        Schema::create('divorce_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('profile_roles')->onDelete('cascade');
            $table->foreignId('father_id')->constrained('profile_roles')->onDelete('cascade');
            $table->string('case_no');
            $table->date('divorce_date');
            $table->string('court_document');
            $table->tinyInteger('status')->default(StatusEnum::Pending->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('divorce_cases');
    }
}

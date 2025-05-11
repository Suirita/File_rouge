<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('question_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_response_id')->constrained('question_responses')->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('trainers')->cascadeOnDelete();
            $table->float('score');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_evaluations');
    }
}

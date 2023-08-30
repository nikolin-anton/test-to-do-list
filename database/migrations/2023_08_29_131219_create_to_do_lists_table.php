<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Enum\StatusList;
use Illuminate\Support\Arr;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('status', Arr::pluck(StatusList::cases(), 'value'))->nullable();
            $table->unsignedTinyInteger('priority');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('to_do_lists');
    }
};

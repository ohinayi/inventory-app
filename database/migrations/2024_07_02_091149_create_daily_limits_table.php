<?php

use App\Models\Employee;
use App\Models\Item;
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
        Schema::create('daily_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Item::class)->constrained()->onDelete('cascade');
            $table->integer('limit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_limits');
    }
};

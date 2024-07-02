<?php

use App\Models\Item;
use App\Models\Procurement;
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
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_approved')->default(false);
            $table->foreignIdFor(Item::class)->constrained();
            $table->foreignIdFor(Procurement::class)->constrained()->onDelete('cascade');
            $table->integer('quantity_received')->default(0);
            $table->integer('quantity_requested');
            $table->text('receive_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_items');
    }
};

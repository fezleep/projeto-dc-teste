return new class extends Migration {
    public function up(): void {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->date('due_date');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('installments');
    }
};

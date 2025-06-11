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
            Schema::create('penjualan_makanan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade');
                $table->foreignId('makanan_id')->constrained('makanan')->onDelete('cascade');
                $table->integer('harga_beli');
                $table->integer('harga_jual');
                $table->integer('jml');
                $table->date('tgl');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('penjualan_makanan');
        }
    };
    
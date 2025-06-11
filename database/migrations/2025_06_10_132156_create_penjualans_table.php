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
                Schema::create('penjualan', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('pembeli_id')->constrained('pembeli'); // Foreign key ke tabel pembeli
                    $table->string('no_faktur')->unique(); // Nomor faktur unik
                    $table->string('status')->default('pesan'); // Status penjualan (pesan, bayar, kirim)
                    $table->dateTime('tgl'); // Tanggal dan waktu
                    $table->decimal('tagihan', 15, 2)->default(0); // Tagihan, decimal dengan 15 digit total, 2 desimal
                    $table->timestamps();
                });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('penjualan');
            }
        };
        
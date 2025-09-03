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
        Schema::create('telaah_obat_resep_konselings', function (Blueprint $table) {
            $table->id();
            $table->string("noresep");
            $table->string("kejelasanTulisanResep");
            $table->string("beratbadan")->nullable();
            $table->string("identitasObat");
            $table->string("tepatObat");
            $table->string("tepatDosis");
            $table->string("tepatRute");
            $table->string("tepatWaktu");
            $table->string("duplikasi");
            $table->string("alergi");
            $table->string("interaksiObat");
            $table->string("kontraIndikasiLainnya");
            $table->string("kesesuaianIdentitasPasienResep");
            $table->string("namaObatResep");
            $table->string("dosisResep");
            $table->string("ruteCaraResep");
            $table->string("waktuResep");
            $table->string("polifarmasi");
            $table->string("obatluar");
            $table->string("alatkhusus");
            $table->string("antibiotik");
            $table->string("pm");
            $table->string("efeksamping");
            $table->string("indeksterapisempit");
            $table->string("interaksiobatKonseling");
            $table->string("interaksiobatmakanan");
            $table->string("tepatObatKonseling");
            $table->string("tepatInformasiKonseling");
            $table->string("tepatDokumentasiKonseling");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telaah_obat_resep_konselings');
    }
};

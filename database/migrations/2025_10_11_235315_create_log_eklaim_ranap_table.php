<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_eklaim_ranap', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sep')->nullable();
            $table->string('nomor_kartu')->nullable();
            $table->string('nomor_rm')->nullable();
            $table->string('nama_pasien')->nullable();
            $table->string('nama_dokter')->nullable();
            $table->dateTime('tgl_masuk')->nullable();
            $table->dateTime('tgl_pulang')->nullable();
            $table->string('cara_masuk')->nullable();
            $table->tinyInteger('jenis_rawat')->nullable();
            $table->tinyInteger('kelas_rawat')->nullable();
            $table->tinyInteger('discharge_status')->nullable();
            $table->tinyInteger('adl_sub_acute')->nullable();
            $table->tinyInteger('adl_chronic')->nullable();
            $table->tinyInteger('icu_indikator')->nullable();
            $table->integer('icu_los')->nullable();
            $table->tinyInteger('upgrade_class_ind')->nullable();
            $table->integer('upgrade_class_los')->nullable();
            $table->integer('add_payment_pct')->nullable();
            $table->integer('birth_weight')->nullable();
            $table->integer('sistole')->nullable();
            $table->integer('diastole')->nullable();
            $table->tinyInteger('dializer_single_use')->nullable();
            $table->tinyInteger('tb_indikator')->nullable();
            $table->tinyInteger('pemulasaraan_jenazah')->nullable();
            $table->tinyInteger('kantong_jenazah')->nullable();
            $table->tinyInteger('peti_jenazah')->nullable();
            $table->tinyInteger('desinfektan_jenazah')->nullable();
            $table->tinyInteger('mobil_jenazah')->nullable();
            $table->tinyInteger('desinfektan_mobil_jenazah')->nullable();
            $table->string('covid19_status_cd')->nullable();
            $table->string('nomor_kartu_t')->nullable();
            $table->json('tarif_rs')->nullable();
            $table->string('episodes')->nullable();
            $table->string('payor_id')->nullable();
            $table->string('payor_cd')->nullable();
            $table->string('kode_tarif')->nullable();
            $table->string('coder_nik')->nullable();

            // kolom diagnosa / procedure
            $table->json('diagnosa_idrg')->nullable();
            $table->json('procedure_idrg')->nullable();
            $table->json('diagnosa_inacbg')->nullable();
            $table->json('procedure_inacbg')->nullable();

            // log response
            $table->json('response_new_claim')->nullable();
            $table->json('response_set_claim_data')->nullable();

            $table->string('status')->default('pending'); // pending/proses klaim
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_eklaim_ranap');
    }
};

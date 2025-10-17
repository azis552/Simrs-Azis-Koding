<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEklaimRajal extends Model
{
    use HasFactory;
   protected $table = 'log_eklaim_rajal';

    protected $fillable = [
        'nomor_sep',
        'nomor_kartu',
        'nomor_rm',
        'nama_pasien',
        'nama_dokter',
        'tgl_masuk',
        'tgl_pulang',
        'cara_masuk',
        'jenis_rawat',
        'kelas_rawat',
        'discharge_status',
        'adl_sub_acute',
        'adl_chronic',
        'icu_indikator',
        'icu_los',
        'upgrade_class_ind',
        'upgrade_class_los',
        'add_payment_pct',
        'birth_weight',
        'sistole',
        'diastole',
        'dializer_single_use',
        'tb_indikator',
        'pemulasaraan_jenazah',
        'kantong_jenazah',
        'peti_jenazah',
        'desinfektan_jenazah',
        'mobil_jenazah',
        'desinfektan_mobil_jenazah',
        'covid19_status_cd',
        'nomor_kartu_t',
        'tarif_rs',
        'episodes',
        'payor_id',
        'payor_cd',
        'kode_tarif',
        'coder_nik',

        // kolom diagnosa / procedure
        'diagnosa_idrg',
        'procedure_idrg',
        'diagnosa_inacbg',
        'procedure_inacbg',

        // response API
        'response_new_claim',
        'response_set_claim_data',
        'response_grouping_idrg',
        'response_idrg_grouper_final',
        'response_claim_final',
        'response_send_claim_individual',
        'status'
    ];

    protected $casts = [
        'tarif_rs' => 'array',
        'diagnosa_idrg' => 'array',
        'procedure_idrg' => 'array',
        'diagnosa_inacbg' => 'array',
        'procedure_inacbg' => 'array',
        'response_new_claim' => 'array',
        'response_set_claim_data' => 'array'
    ];

}

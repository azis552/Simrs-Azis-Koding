<?php

namespace App\Http\Controllers;

use App\Models\LogEklaimRanap;
use App\Services\Eklaimservice;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class InacbgRanapController extends Controller
{
    public function __construct(Eklaimservice $eklaim)
    {
        $this->eklaim = $eklaim;
    }

    // =========================================================
    // INDEX — daftar pasien rawat inap
    // BUG FIX: view('inacbg.ranap') → view('inacbg.klaim-ranap')
    // Sebelumnya memanggil view yang tidak ada → Internal Server Error
    // =========================================================
    public function index(Request $request)
    {
        $search = $request->get('key');
        $order  = $request->get('order', 'kamar_inap.tgl_masuk DESC');

        $data = DB::table('kamar_inap')
            ->selectRaw("
                kamar_inap.no_rawat,
                reg_periksa.no_rkm_medis,
                pasien.nm_pasien,
                concat(pasien.alamat, ', ', kelurahan.nm_kel, ', ', kecamatan.nm_kec, ', ', kabupaten.nm_kab) as alamat,
                reg_periksa.p_jawab,
                reg_periksa.hubunganpj,
                penjab.png_jawab,
                concat(kamar_inap.kd_kamar,' ',bangsal.nm_bangsal) as kamar,
                kamar_inap.trf_kamar,
                kamar_inap.diagnosa_awal,
                kamar_inap.diagnosa_akhir,
                kamar_inap.tgl_masuk,
                kamar_inap.jam_masuk,
                if(kamar_inap.tgl_keluar='0000-00-00','',kamar_inap.tgl_keluar) as tgl_keluar,
                if(kamar_inap.jam_keluar='00:00:00','',kamar_inap.jam_keluar) as jam_keluar,
                kamar_inap.ttl_biaya,
                kamar_inap.stts_pulang,
                kamar_inap.lama,
                dokter.nm_dokter,
                kamar_inap.kd_kamar,
                reg_periksa.kd_pj,
                concat(reg_periksa.umurdaftar,' ',reg_periksa.sttsumur) as umur,
                reg_periksa.status_bayar,
                pasien.agama
            ")
            ->join('reg_periksa',  'kamar_inap.no_rawat',      '=', 'reg_periksa.no_rawat')
            ->join('pasien',       'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('kamar',        'kamar_inap.kd_kamar',      '=', 'kamar.kd_kamar')
            ->join('bangsal',      'kamar.kd_bangsal',         '=', 'bangsal.kd_bangsal')
            ->join('kelurahan',    'pasien.kd_kel',            '=', 'kelurahan.kd_kel')
            ->join('kecamatan',    'pasien.kd_kec',            '=', 'kecamatan.kd_kec')
            ->join('kabupaten',    'pasien.kd_kab',            '=', 'kabupaten.kd_kab')
            ->join('dokter',       'reg_periksa.kd_dokter',    '=', 'dokter.kd_dokter')
            ->join('penjab',       'reg_periksa.kd_pj',        '=', 'penjab.kd_pj')
            ->join('bridging_sep', function ($join) {
                $join->on('kamar_inap.no_rawat', '=', 'bridging_sep.no_rawat')
                     ->where('bridging_sep.jnspelayanan', '1');
            })
            ->whereNotNull('kamar_inap.tgl_keluar')
            ->where('kamar_inap.tgl_keluar',  '<>', '0000-00-00')
            ->where('kamar_inap.stts_pulang', '<>', '')
            ->where('kamar_inap.stts_pulang', '<>', 'Pindah Kamar')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kamar_inap.no_rawat',        'like', "%{$search}%")
                        ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$search}%")
                        ->orWhere('pasien.nm_pasien',          'like', "%{$search}%")
                        ->orWhere(DB::raw("concat(pasien.alamat, ', ', kelurahan.nm_kel, ', ', kecamatan.nm_kec, ', ', kabupaten.nm_kab)"), 'like', "%{$search}%")
                        ->orWhere('kamar_inap.kd_kamar',       'like', "%{$search}%")
                        ->orWhere('bangsal.nm_bangsal',        'like', "%{$search}%")
                        ->orWhere('kamar_inap.diagnosa_awal',  'like', "%{$search}%")
                        ->orWhere('kamar_inap.diagnosa_akhir', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.tgl_masuk',      'like', "%{$search}%")
                        ->orWhere('kamar_inap.tgl_keluar',     'like', "%{$search}%")
                        ->orWhere('dokter.nm_dokter',          'like', "%{$search}%")
                        ->orWhere('kamar_inap.stts_pulang',    'like', "%{$search}%")
                        ->orWhere('penjab.png_jawab',          'like', "%{$search}%")
                        ->orWhere('pasien.agama',              'like', "%{$search}%");
                });
            })
            ->where('reg_periksa.kd_pj', '<>', 'UMUM')
            ->distinct('kamar_inap.no_rawat')
            ->orderByRaw($order)
            ->paginate(10)
            ->withQueryString();

        // BUG FIX: 'inacbg.ranap' → 'inacbg.ranap-list'
        // Buat file resources/views/inacbg/ranap-list.blade.php untuk halaman daftar,
        // dan resources/views/inacbg/klaim.blade.php untuk halaman detail klaim.
        return view('inacbg.ranap-list', compact('data'));
    }

    // =========================================================
    // SHOW — detail klaim satu pasien
    // BUG FIX #1: $sep di-query DUA KALI (baris ~109 dan ~131)
    //             Query pertama tidak dipakai sama sekali jika $log ada.
    //             Cukup query sekali di awal.
    // BUG FIX #2: Jika $log ada, return view tanpa $sep/$bayi/$pemeriksaan/dll
    //             sehingga blade error "Undefined variable $sep".
    //             Sekarang semua variabel selalu di-pass ke view.
    // =========================================================
    public function show(Request $request)
    {
        $no_rawat = $request->input('no_rawat');

        if (empty($no_rawat)) {
            return redirect()->back()->with('error', 'Parameter no_rawat diperlukan.');
        }

        // BUG FIX #1: query $sep sekali saja di awal
        $sep = DB::table('bridging_sep')
            ->where('no_rawat', $no_rawat)
            ->where('jnspelayanan', '1')
            ->first();

        if (!$sep) {
            return redirect()->back()->with('error', 'Data SEP tidak ditemukan.');
        }

        $log = LogEklaimRanap::where('nomor_sep', $sep->no_sep)->first();

        $pasien = DB::table('kamar_inap')
            ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien',      'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('dokter',      'reg_periksa.kd_dokter',    '=', 'dokter.kd_dokter')
            ->select(
                'kamar_inap.no_rawat',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien',
                'pasien.tgl_lahir',
                'pasien.jk',
                'pasien.alamat',
                'dokter.nm_dokter',
                'pasien.no_peserta',
                'reg_periksa.kd_pj as jenis_pasien',
                'reg_periksa.umurdaftar as umur',
                'kamar_inap.tgl_masuk',
                'kamar_inap.jam_masuk',
                'kamar_inap.tgl_keluar',
                'kamar_inap.jam_keluar',
                'kamar_inap.lama',
                'kamar_inap.stts_pulang as cara_pulang',
                'reg_periksa.kd_pj as cara_masuk',
                DB::raw("'-' as jenis_rawat"),
                DB::raw("'123456' as no_sep"),
                DB::raw("'INV042025.1396' as no_tagihan")
            )
            ->where('kamar_inap.no_rawat', $no_rawat)
            ->first();

        if (!$pasien) {
            return redirect()->back()->with('error', 'Data pasien tidak ditemukan.');
        }

        $bayi = DB::table('penilaian_awal_keperawatan_ralan_bayi')
            ->where('no_rawat', $no_rawat)
            ->first();

        $pemeriksaan = DB::table('pemeriksaan_ranap')
            ->where('no_rawat', $no_rawat)
            ->first();

        $coder = DB::table('mapping_users')
            ->join('pegawai', 'mapping_users.id_pegawai', '=', 'pegawai.id')
            ->where('id_users', auth()->user()->id)
            ->first();

        $obatbhpalkes = DB::table('detail_pemberian_obat')
            ->join('databarang', 'detail_pemberian_obat.kode_brng', '=', 'databarang.kode_brng')
            ->join('jenis',      'databarang.kdjns',                '=', 'jenis.kdjns')
            ->selectRaw("
                SUM(CASE WHEN jenis.nama REGEXP 'OBAT|Tablet|Syrup|Salep|Infus|Injeksi|Cairan|Suntik|Ampul|Elixir|OBAT LUAR|OBAT NON ORAL|OBAT ORAL'
                    THEN detail_pemberian_obat.total ELSE 0 END) AS total_obat,
                SUM(CASE WHEN jenis.nama REGEXP 'BHP|NON BHP'
                    THEN detail_pemberian_obat.total ELSE 0 END) AS total_bhp,
                SUM(CASE WHEN jenis.nama REGEXP 'ALKES'
                    THEN detail_pemberian_obat.total ELSE 0 END) AS total_alkes
            ")
            ->where('detail_pemberian_obat.no_rawat', $no_rawat)
            ->first();

        $sql = <<<SQL
            SELECT rjdr.no_rawat, jp.kd_jenis_prw AS kode_tindakan, jp.nm_perawatan,
                COUNT(rjdr.kd_jenis_prw) AS jml, jp.total_byrdr AS biaya,
                SUM(rjdr.biaya_rawat) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM rawat_jl_dr rjdr
            JOIN jns_perawatan jp ON rjdr.kd_jenis_prw = jp.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jp.kd_jenis_prw = rjt.kode_tindakan
            WHERE rjdr.no_rawat = ?
            GROUP BY rjdr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan, jp.total_byrdr, rjt.jenis_tindakan

            UNION ALL

            SELECT rjpr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan,
                COUNT(rjpr.kd_jenis_prw), jp.total_byrdr, SUM(rjpr.biaya_rawat),
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM rawat_jl_pr rjpr
            JOIN jns_perawatan jp ON rjpr.kd_jenis_prw = jp.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jp.kd_jenis_prw = rjt.kode_tindakan
            WHERE rjpr.no_rawat = ?
            GROUP BY rjpr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan, jp.total_byrdr, rjt.jenis_tindakan

            UNION ALL

            SELECT rjdpr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan,
                COUNT(rjdpr.kd_jenis_prw), jp.total_byrdrpr, SUM(rjdpr.biaya_rawat),
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM rawat_jl_drpr rjdpr
            JOIN jns_perawatan jp ON rjdpr.kd_jenis_prw = jp.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jp.kd_jenis_prw = rjt.kode_tindakan
            WHERE rjdpr.no_rawat = ?
            GROUP BY rjdpr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan, jp.total_byrdrpr, rjt.jenis_tindakan

            UNION ALL

            SELECT ridr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan,
                COUNT(ridr.kd_jenis_prw), jpi.total_byrdr, SUM(ridr.biaya_rawat),
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM rawat_inap_dr ridr
            JOIN jns_perawatan_inap jpi ON ridr.kd_jenis_prw = jpi.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpi.kd_jenis_prw = rjt.kode_tindakan
            WHERE ridr.no_rawat = ?
            GROUP BY ridr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan, jpi.total_byrdr, rjt.jenis_tindakan

            UNION ALL

            SELECT ridpr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan,
                COUNT(ridpr.kd_jenis_prw), jpi.total_byrdrpr, SUM(ridpr.biaya_rawat),
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM rawat_inap_drpr ridpr
            JOIN jns_perawatan_inap jpi ON ridpr.kd_jenis_prw = jpi.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpi.kd_jenis_prw = rjt.kode_tindakan
            WHERE ridpr.no_rawat = ?
            GROUP BY ridpr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan, jpi.total_byrdrpr, rjt.jenis_tindakan

            UNION ALL

            SELECT ripr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan,
                COUNT(ripr.kd_jenis_prw), jpi.total_byrpr, SUM(ripr.biaya_rawat),
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM rawat_inap_pr ripr
            JOIN jns_perawatan_inap jpi ON ripr.kd_jenis_prw = jpi.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpi.kd_jenis_prw = rjt.kode_tindakan
            WHERE ripr.no_rawat = ?
            GROUP BY ripr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan, jpi.total_byrpr, rjt.jenis_tindakan

            UNION ALL

            SELECT pl.no_rawat, jpl.kd_jenis_prw, jpl.nm_perawatan,
                COUNT(pl.kd_jenis_prw), pl.biaya, SUM(pl.biaya),
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM periksa_lab pl
            JOIN jns_perawatan_lab jpl ON pl.kd_jenis_prw = jpl.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpl.kd_jenis_prw = rjt.kode_tindakan
            WHERE pl.no_rawat = ?
            GROUP BY pl.no_rawat, jpl.kd_jenis_prw, jpl.nm_perawatan, pl.biaya, rjt.jenis_tindakan

            UNION ALL

            SELECT pr.no_rawat, jpr.kd_jenis_prw, jpr.nm_perawatan,
                COUNT(pr.kd_jenis_prw), pr.biaya, SUM(pr.biaya),
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM periksa_radiologi pr
            JOIN jns_perawatan_radiologi jpr ON pr.kd_jenis_prw = jpr.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpr.kd_jenis_prw = rjt.kode_tindakan
            WHERE pr.no_rawat = ?
            GROUP BY pr.no_rawat, jpr.kd_jenis_prw, jpr.nm_perawatan, pr.biaya, rjt.jenis_tindakan

            UNION ALL

            SELECT o.no_rawat, o.kode_paket, p.nm_perawatan, 1,
                (IFNULL(p.operator1,0)+IFNULL(p.operator2,0)+IFNULL(p.operator3,0)+
                 IFNULL(p.asisten_operator1,0)+IFNULL(p.asisten_operator2,0)+IFNULL(p.asisten_operator3,0)+
                 IFNULL(p.instrumen,0)+IFNULL(p.dokter_anak,0)+IFNULL(p.perawaat_resusitas,0)+
                 IFNULL(p.dokter_anestesi,0)+IFNULL(p.asisten_anestesi,0)+IFNULL(p.asisten_anestesi2,0)+
                 IFNULL(p.bidan,0)+IFNULL(p.bidan2,0)+IFNULL(p.bidan3,0)+
                 IFNULL(p.perawat_luar,0)+IFNULL(p.sewa_ok,0)+IFNULL(p.alat,0)+
                 IFNULL(p.akomodasi,0)+IFNULL(p.bagian_rs,0)+IFNULL(p.omloop,0)+
                 IFNULL(p.omloop2,0)+IFNULL(p.omloop3,0)+IFNULL(p.omloop4,0)+
                 IFNULL(p.omloop5,0)+IFNULL(p.sarpras,0)+IFNULL(p.dokter_pjanak,0)+IFNULL(p.dokter_umum,0)) AS biaya,
                (IFNULL(p.operator1,0)+IFNULL(p.operator2,0)+IFNULL(p.operator3,0)+
                 IFNULL(p.asisten_operator1,0)+IFNULL(p.asisten_operator2,0)+IFNULL(p.asisten_operator3,0)+
                 IFNULL(p.instrumen,0)+IFNULL(p.dokter_anak,0)+IFNULL(p.perawaat_resusitas,0)+
                 IFNULL(p.dokter_anestesi,0)+IFNULL(p.asisten_anestesi,0)+IFNULL(p.asisten_anestesi2,0)+
                 IFNULL(p.bidan,0)+IFNULL(p.bidan2,0)+IFNULL(p.bidan3,0)+
                 IFNULL(p.perawat_luar,0)+IFNULL(p.sewa_ok,0)+IFNULL(p.alat,0)+
                 IFNULL(p.akomodasi,0)+IFNULL(p.bagian_rs,0)+IFNULL(p.omloop,0)+
                 IFNULL(p.omloop2,0)+IFNULL(p.omloop3,0)+IFNULL(p.omloop4,0)+
                 IFNULL(p.omloop5,0)+IFNULL(p.sarpras,0)+IFNULL(p.dokter_pjanak,0)+IFNULL(p.dokter_umum,0)) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan')
            FROM operasi o
            JOIN paket_operasi p ON o.kode_paket = p.kode_paket
            LEFT JOIN relasi_jenis_tindakan rjt ON p.kode_paket = rjt.kode_tindakan
            WHERE o.no_rawat = ?
        SQL;

        $rows      = DB::select($sql, array_fill(0, 9, $no_rawat));
        $rekap     = collect($rows)
            ->groupBy(fn($item) => strtolower(trim($item->jenis_tindakan)))
            ->map(fn($items) => (float) $items->sum(fn($it) => (float) ($it->total ?? 0)))
            ->mapWithKeys(fn($value, $key) => [ucfirst($key) => $value])
            ->toArray();

        $obatbhpalkes = (array) $obatbhpalkes;
        $totalKamar   = DB::table('kamar_inap')->where('no_rawat', $no_rawat)->sum('ttl_biaya') ?? 0;

        // BUG FIX #2: selalu pass SEMUA variabel ke view, baik $log ada maupun tidak
        // Sebelumnya: jika $log ada → return view hanya dengan compact('pasien','log')
        // sehingga blade error "Undefined variable $sep", "$bayi", "$pemeriksaan", dst.
        return view('inacbg.klaim', compact(
            'pasien', 'log', 'sep', 'bayi', 'pemeriksaan',
            'coder', 'obatbhpalkes', 'rekap', 'totalKamar'
        ));
    }

    // =========================================================
    // STORE — simpan data klaim
    // BUG FIX: $this->show($requestShow) mengembalikan Response,
    // tidak bisa di-chain dengan ->with(). Ganti ke redirect()->route()
    // (sudah benar di kode asli, tapi blok yang di-comment masih salah)
    // =========================================================
    public function store(Request $request)
    {
        $input = $request->all();

        $logExisting = LogEklaimRanap::where('nomor_sep', $input['nomor_sep'] ?? null)->first();
        if ($logExisting && $logExisting->status === 'proses final idrg') {
            return redirect()
                ->route('inacbg-ranap.show', ['no_rawat' => $input['no_rawat']])
                ->with('info', 'Data sudah Final IDRG, tidak dapat disimpan ulang.');
        }

        if (!empty($input['tgl_masuk'])) {
            $input['tgl_masuk'] = Carbon::parse($input['tgl_masuk'])->format('Y-m-d H:i:s');
        }
        if (!empty($input['tgl_pulang'])) {
            $input['tgl_pulang'] = Carbon::parse($input['tgl_pulang'])->format('Y-m-d H:i:s');
        }

        // BUG FIX: tarif_rs dikirim sebagai array dari form, harus di-encode ke JSON
        if (!empty($input['tarif_rs']) && is_array($input['tarif_rs'])) {
            $input['tarif_rs'] = json_encode($input['tarif_rs']);
        }

        $log = LogEklaimRanap::updateOrCreate(
            ['nomor_sep' => $input['nomor_sep']],
            [
                'nomor_kartu'               => $input['nomor_kartu']               ?? null,
                'tgl_masuk'                 => $input['tgl_masuk']                 ?? null,
                'tgl_pulang'                => $input['tgl_pulang']                ?? null,
                'cara_masuk'                => $input['cara_masuk']                ?? null,
                'discharge_status'          => $input['discharge_status']          ?? null,
                'adl_sub_acute'             => $input['adl_sub_acute']             ?? null,
                'adl_chronic'               => $input['adl_chronic']               ?? null,
                'icu_indikator'             => $input['icu_indikator']             ?? null,
                'icu_los'                   => $input['icu_los']                   ?? null,
                'upgrade_class_ind'         => $input['upgrade_class_ind']         ?? null,
                'upgrade_class_los'         => $input['upgrade_class_los']         ?? null,
                'add_payment_pct'           => $input['add_payment_pct']           ?? null,
                'birth_weight'              => $input['birth_weight']              ?? null,
                'sistole'                   => $input['sistole']                   ?? null,
                'diastole'                  => $input['diastole']                  ?? null,
                'dializer_single_use'       => $input['dializer_single_use']       ?? null,
                'tb_indikator'              => $input['tb_indikator']              ?? null,
                'pemulasaraan_jenazah'      => $input['pemulasaraan_jenazah']      ?? null,
                'kantong_jenazah'           => $input['kantong_jenazah']           ?? null,
                'peti_jenazah'              => $input['peti_jenazah']              ?? null,
                'desinfektan_jenazah'       => $input['desinfektan_jenazah']       ?? null,
                'mobil_jenazah'             => $input['mobil_jenazah']             ?? null,
                'desinfektan_mobil_jenazah' => $input['desinfektan_mobil_jenazah'] ?? null,
                'covid19_status_cd'         => $input['covid19_status_cd']         ?? null,
                'nomor_kartu_t'             => $input['nomor_kartu_t']             ?? null,
                'tarif_rs'                  => $input['tarif_rs']                  ?? null,
                'episodes'                  => $input['episodes']                  ?? null,
                'payor_id'                  => $input['payor_id']                  ?? null,
                'payor_cd'                  => $input['payor_cd']                  ?? null,
                'kode_tarif'                => $input['kode_tarif']                ?? null,
                'coder_nik'                 => $input['coder_nik']                 ?? null,
                'kelas_rawat'               => $input['kelas_rawat']               ?? null,
                'jenis_rawat'               => $input['jenis_rawat']               ?? null,
                'nomor_rm'                  => $input['nomor_rm']                  ?? null,
                'nama_pasien'               => $input['nama_pasien']               ?? null,
                'nama_dokter'               => $input['nama_dokter']               ?? null,
                'status'                    => 'proses klaim',
            ]
        );

        $payloadNew = [
            'nomor_sep'   => $input['nomor_sep'],
            'nomor_kartu' => $input['nomor_kartu'],
            'nomor_rm'    => $input['nomor_rm'],
            'nama_pasien' => $input['nama_pasien'],
            'tgl_lahir'   => date('Y-m-d H:i:s', strtotime($input['tgl_lahir'])),
            'gender'      => $input['gender'],
        ];

        $responseNew = $this->eklaim->send('new_claim', $payloadNew);
        $log->update(['response_new_claim' => $responseNew]);

        $responseSet = $this->eklaim->send('set_claim_data', $input, [
            'method'     => 'set_claim_data',
            'nomor_sep'  => $payloadNew['nomor_sep'] ?? null,
        ]);

        $status = (isset($responseSet['metadata']['code']) && $responseSet['metadata']['code'] == 200)
            ? 'proses klaim'
            : 'gagal';

        $log->update(['response_set_claim_data' => $responseSet, 'status' => $status]);

        return $status === 'proses klaim'
            ? redirect()->route('inacbg-ranap.show', ['no_rawat' => $input['no_rawat']])->with('success', 'Berhasil mengirim e-Klaim')
            : redirect()->route('inacbg-ranap.show', ['no_rawat' => $input['no_rawat']])->with('error', 'Gagal mengirim e-Klaim');
    }

    // =========================================================
    // HAPUS KLAIM
    // BUG FIX: $this->show($requestShow)->with(...) tidak valid
    // karena show() mengembalikan Response bukan Redirector.
    // Ganti ke redirect()->route()
    // =========================================================
    public function hapusKlaim(Request $request)
    {
        $response = $this->eklaim->send('delete_claim', [
            'nomor_sep' => $request->nomor_sep,
            'coder_nik' => $request->coder_nik,
        ]);

        LogEklaimRanap::where('nomor_sep', $request->nomor_sep)->delete();

        $status = $response['metadata']['code'] ?? null;

        // BUG FIX: ganti $this->show(...)->with() → redirect()->route()
        return $status == 200
            ? redirect()->route('inacbg-ranap.show', ['no_rawat' => $request->no_rawat])->with('success', 'Berhasil menghapus e-Klaim.')
            : redirect()->route('inacbg-ranap.show', ['no_rawat' => $request->no_rawat])->with('error', 'Gagal menghapus e-Klaim.');
    }

    // =========================================================
    // Semua method save/update log di bawah ini tidak berubah
    // =========================================================

    public function updateLog(Request $request)
    {
        DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->input('nomor_sep'))
            ->update([$request->input('field') => $request->input('value')]);

        return response()->json(['status' => 'ok', 'updated_field' => $request->input('field')]);
    }

    public function saveGroupingIdrgLog(Request $request)
    {
        DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update(['response_grouping_idrg' => $request->response_grouping_idrg]);

        return response()->json(['status' => 'success']);
    }

    public function saveFinalIdrgLog(Request $request)
    {
        $request->validate([
            'nomor_sep'                  => 'required|string|max:50',
            'response_idrg_grouper_final' => 'required|json',
        ]);

        $updated = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update([
                'status'                     => 'proses final idrg',
                'response_idrg_grouper_final' => $request->response_idrg_grouper_final,
                'updated_at'                 => now(),
            ]);

        return response()->json([
            'status'  => $updated ? 'success' : 'failed',
            'message' => $updated ? 'Final IDRG berhasil disimpan' : 'Nomor SEP tidak ditemukan',
        ]);
    }

    public function hapusFinalIdrg(Request $request)
    {
        $nomor_sep = $request->input('nomor_sep');

        if (!$nomor_sep) {
            return response()->json(['status' => 'error', 'message' => 'Nomor SEP tidak ditemukan'], 400);
        }

        DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $nomor_sep)
            ->update([
                'procedure_inacbg'            => null,
                'diagnosa_inacbg'             => null,
                'response_grouping_idrg'      => null,
                'status'                      => 'proses klaim',
                'response_idrg_grouper_final' => null,
            ]);

        // BUG FIX: typo 'mes`sage' → 'message'
        return response()->json(['status' => 'success', 'message' => 'Final IDRG berhasil dihapus']);
    }

    public function saveImportInacbgLog(Request $request)
    {
        $request->validate([
            'nomor_sep'              => 'required|string|max:50',
            'response_inacbg_import' => 'required|json',
        ]);

        $response      = json_decode($request->response_inacbg_import, true);
        $diagnosaData  = $response['data']['diagnosa']  ?? null;
        $procedureData = $response['data']['procedure'] ?? null;

        $updated = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update([
                'status'                 => 'proses final idrg',
                'response_inacbg_import' => $request->response_inacbg_import,
                'diagnosa_inacbg'        => $diagnosaData  ? json_encode($diagnosaData,  JSON_UNESCAPED_UNICODE) : null,
                'procedure_inacbg'       => $procedureData ? json_encode($procedureData, JSON_UNESCAPED_UNICODE) : null,
                'updated_at'             => now(),
            ]);

        return response()->json([
            'status'  => $updated ? 'success' : 'failed',
            'message' => $updated ? 'Hasil import berhasil disimpan' : 'Nomor SEP tidak ditemukan',
        ]);
    }

    public function saveGroupingStage1Log(Request $request)
    {
        $request->validate([
            'nomor_sep'               => 'required|string|max:50',
            'response_inacbg_stage1'  => 'required|json',
        ]);

        $updated = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update(['response_inacbg_stage1' => $request->response_inacbg_stage1, 'updated_at' => now()]);

        return response()->json(['status' => $updated ? 'success' : 'failed']);
    }

    public function saveGroupingStage2Log(Request $request)
    {
        $request->validate([
            'nomor_sep'              => 'required|string|max:50',
            'response_inacbg_stage2' => 'nullable|json',
        ]);

        $updated = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update(['response_inacbg_stage2' => $request->response_inacbg_stage2, 'updated_at' => now()]);

        return response()->json(['status' => $updated ? 'success' : 'failed']);
    }

    public function saveFinalInacbgLog(Request $request)
    {
        $request->validate([
            'nomor_sep'              => 'required|string|max:50',
            'response_inacbg_final'  => 'required|json',
        ]);

        $updated = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update(['response_inacbg_final' => $request->response_inacbg_final, 'updated_at' => now()]);

        return response()->json([
            'status'  => $updated ? 'success' : 'failed',
            'message' => $updated ? 'Log Final INACBG berhasil disimpan.' : 'Gagal menyimpan.',
        ]);
    }

    public function reeditGroupingInacbg(Request $request)
    {
        $nomor_sep = $request->input('nomor_sep');

        if (!$nomor_sep) {
            return response()->json(['status' => 'error', 'message' => 'Nomor SEP tidak ditemukan'], 400);
        }

        $hasFinal = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $nomor_sep)
            ->whereNotNull('response_inacbg_final')
            ->exists();

        if (!$hasFinal) {
            return response()->json(['status' => 'error', 'message' => 'Belum ada Final INACBG, re-edit tidak dapat dilakukan.'], 400);
        }

        DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $nomor_sep)
            ->update([
                'response_inacbg_stage1' => null,
                'response_inacbg_stage2' => null,
                'response_inacbg_final'  => null,
                'updated_at'             => now(),
            ]);

        try {
            $result = app(\App\Services\Eklaimservice::class)
                ->send('inacbg_grouper_reedit', ['nomor_sep' => $nomor_sep], ['method' => 'inacbg_grouper_reedit']);

            return response()->json(['status' => 'success', 'message' => 'Re-edit INA-CBG berhasil.', 'response' => $result]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal re-edit INA-CBG.', 'error' => $e->getMessage()], 500);
        }
    }

    public function saveClaimFinalLog(Request $request)
    {
        $request->validate([
            'nomor_sep'            => 'required|string|max:50',
            'response_claim_final' => 'required|json',
        ]);

        $updated = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update(['response_claim_final' => $request->response_claim_final, 'updated_at' => now()]);

        return response()->json([
            'status'  => $updated ? 'success' : 'failed',
            'message' => $updated ? 'Log Claim Final berhasil disimpan.' : 'Gagal menyimpan.',
        ]);
    }

    public function reeditClaim(Request $request)
    {
        $nomor_sep = $request->input('nomor_sep');

        if (!$nomor_sep) {
            return response()->json(['status' => 'error', 'message' => 'Nomor SEP tidak ditemukan'], 400);
        }

        $hasFinal = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $nomor_sep)
            ->whereNotNull('response_claim_final')
            ->exists();

        if (!$hasFinal) {
            return response()->json(['status' => 'error', 'message' => 'Belum ada Claim Final, re-edit tidak dapat dilakukan.'], 400);
        }

        DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $nomor_sep)
            ->update(['response_claim_final' => null, 'updated_at' => now()]);

        try {
            $result = app(\App\Services\Eklaimservice::class)
                ->send('reedit_claim', ['nomor_sep' => $nomor_sep], ['method' => 'reedit_claim']);

            return response()->json(['status' => 'success', 'message' => 'Re-edit claim berhasil.', 'response' => $result]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal re-edit claim.', 'error' => $e->getMessage()], 500);
        }
    }

    public function saveClaimSendLog(Request $request)
    {
        $request->validate([
            'nomor_sep'                       => 'required|string|max:50',
            'response_send_claim_individual'  => 'required|json',
        ]);

        $updated = DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $request->nomor_sep)
            ->update(['response_send_claim_individual' => $request->response_send_claim_individual, 'updated_at' => now()]);

        return response()->json([
            'status'  => $updated ? 'success' : 'failed',
            'message' => $updated ? 'Log Kirim Claim berhasil disimpan.' : 'Gagal menyimpan.',
        ]);
    }

    public function deleteResponseGroupinginacbg(Request $request)
    {
        $nomor_sep = $request->input('nomor_sep');

        if (!$nomor_sep) {
            return response()->json(['status' => 'error', 'message' => 'Nomor SEP tidak ditemukan'], 400);
        }

        DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $nomor_sep)
            ->update([
                'response_idrg_grouper_final'      => null,
                'response_inacbg_final'             => null,
                'response_inacbg_import'            => null,
                'response_inacbg_stage1'            => null,
                'response_inacbg_stage2'            => null,
                'procedure_inacbg'                  => null,
                'diagnosa_inacbg'                   => null,
                'response_claim_final'              => null,
                'response_send_claim_individual'    => null,
                'status'                            => 'proses klaim',
            ]);

        return response()->json(['status' => 'success', 'message' => 'Response grouping INACBG berhasil dihapus']);
    }

    public function deleteResponseGroupingIdrg(Request $request)
    {
        $nomor_sep = $request->input('nomor_sep');

        if (!$nomor_sep) {
            return response()->json(['status' => 'error', 'message' => 'Nomor SEP tidak ditemukan'], 400);
        }

        DB::table('log_eklaim_ranap')
            ->where('nomor_sep', $nomor_sep)
            ->update([
                'response_grouping_idrg'            => null,
                'response_idrg_grouper_final'       => null,
                'response_inacbg_final'             => null,
                'response_inacbg_import'            => null,
                'response_inacbg_stage1'            => null,
                'response_inacbg_stage2'            => null,
                'procedure_inacbg'                  => null,
                'diagnosa_inacbg'                   => null,
                'response_claim_final'              => null,
                'response_send_claim_individual'    => null,
                'status'                            => 'proses klaim',
            ]);

        return response()->json(['status' => 'success', 'message' => 'Response grouping IDRG berhasil dihapus']);
    }
}
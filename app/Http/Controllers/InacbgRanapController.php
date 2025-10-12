<?php

namespace App\Http\Controllers;

use App\Models\LogEklaimRanap;
use App\Services\EklaimService;
use Carbon\Carbon;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InacbgRanapController extends Controller
{
    public function __construct(EklaimService $eklaim)
    {
        $this->eklaim = $eklaim;
    }
    public function index(Request $request)
    {
        // ambil parameter pencarian & urutan
        $search = $request->get('key');   // pencarian
        $order = $request->get('order', 'kamar_inap.tgl_masuk DESC'); // default order

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
            ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->join('kelurahan', 'pasien.kd_kel', '=', 'kelurahan.kd_kel')
            ->join('kecamatan', 'pasien.kd_kec', '=', 'kecamatan.kd_kec')
            ->join('kabupaten', 'pasien.kd_kab', '=', 'kabupaten.kd_kab')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->join('bridging_sep', function ($join) {
                $join->on('kamar_inap.no_rawat', '=', 'bridging_sep.no_rawat')
                    ->where('bridging_sep.jnspelayanan', '1'); // 1 untuk rawat inap
            })
            ->whereNotNull('kamar_inap.tgl_keluar') // sudah keluar
            ->where('kamar_inap.tgl_keluar', '<>', '0000-00-00') // bukan nol
            ->where('kamar_inap.stts_pulang', '<>', '') // status tidak kosong
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kamar_inap.no_rawat', 'like', "%{$search}%")
                        ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$search}%")
                        ->orWhere('pasien.nm_pasien', 'like', "%{$search}%")
                        ->orWhere(DB::raw("concat(pasien.alamat, ', ', kelurahan.nm_kel, ', ', kecamatan.nm_kec, ', ', kabupaten.nm_kab)"), 'like', "%{$search}%")
                        ->orWhere('kamar_inap.kd_kamar', 'like', "%{$search}%")
                        ->orWhere('bangsal.nm_bangsal', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.diagnosa_awal', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.diagnosa_akhir', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.tgl_masuk', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.tgl_keluar', 'like', "%{$search}%")
                        ->orWhere('dokter.nm_dokter', 'like', "%{$search}%")
                        ->orWhere('kamar_inap.stts_pulang', 'like', "%{$search}%")
                        ->orWhere('penjab.png_jawab', 'like', "%{$search}%")
                        ->orWhere('pasien.agama', 'like', "%{$search}%");
                });
            })
            ->where('reg_periksa.kd_pj', '<>', 'UMUM') // bukan pasien umum
            ->distinct('kamar_inap.no_rawat')
            ->orderByRaw($order)
            ->paginate(10)
            ->withQueryString();


        return view('inacbg.ranap', compact('data'));


    }

    public function show(Request $request)
    {
        $no_rawat = $request->input('no_rawat');

        if (empty($no_rawat)) {
            return redirect()->back()->with('error', 'Parameter no_rawat diperlukan.');
        }

        $sep = DB::table('bridging_sep')
            ->where('no_rawat', $no_rawat)
            ->where('jnspelayanan', '1')
            ->first();

        $log = LogEklaimRanap::where('nomor_sep', $sep->no_sep)->first();



        // Ambil data pasien dan rawat inap
        $pasien = DB::table('kamar_inap')
            ->join('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
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

        if ($log) {
            return view('inacbg.klaim', compact('pasien', 'log'));
        }

        $sep = DB::table('bridging_sep')
            ->where('no_rawat', $no_rawat)
            ->where('jnspelayanan', '1')
            ->first();

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
            ->join('jenis', 'databarang.kdjns', '=', 'jenis.kdjns')
            ->selectRaw("
            SUM(CASE 
                WHEN jenis.nama REGEXP 'OBAT|Tablet|Syrup|Salep|Infus|Injeksi|Cairan|Suntik|Ampul|Elixir|OBAT LUAR|OBAT NON ORAL|OBAT ORAL' 
                THEN detail_pemberian_obat.total 
                ELSE 0 
            END) AS total_obat,

            SUM(CASE 
                WHEN jenis.nama REGEXP 'BHP|NON BHP' 
                THEN detail_pemberian_obat.total 
                ELSE 0 
            END) AS total_bhp,

            SUM(CASE 
                WHEN jenis.nama REGEXP 'ALKES' 
                THEN detail_pemberian_obat.total 
                ELSE 0 
            END) AS total_alkes
        ")
            ->where('detail_pemberian_obat.no_rawat', $no_rawat)
            ->first();

        if (empty($no_rawat)) {
            return response()->json(['error' => 'Parameter no_rawat diperlukan'], 400);
        }

        // Query gabungan (UNION ALL). Semua WHERE memakai placeholder ? (9 SELECT => 9 placeholders)
        $sql = <<<SQL
            SELECT 
                rjdr.no_rawat,
                jp.kd_jenis_prw AS kode_tindakan,
                jp.nm_perawatan AS nm_perawatan,
                COUNT(rjdr.kd_jenis_prw) AS jml,
                jp.total_byrdr AS biaya,
                SUM(rjdr.biaya_rawat) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM rawat_jl_dr rjdr
            JOIN jns_perawatan jp ON rjdr.kd_jenis_prw = jp.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jp.kd_jenis_prw = rjt.kode_tindakan
            WHERE rjdr.no_rawat = ?
            GROUP BY rjdr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan, jp.total_byrdr, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                rjpr.no_rawat,
                jp.kd_jenis_prw AS kode_tindakan,
                jp.nm_perawatan AS nm_perawatan,
                COUNT(rjpr.kd_jenis_prw) AS jml,
                jp.total_byrdr AS biaya,
                SUM(rjpr.biaya_rawat) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM rawat_jl_pr rjpr
            JOIN jns_perawatan jp ON rjpr.kd_jenis_prw = jp.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jp.kd_jenis_prw = rjt.kode_tindakan
            WHERE rjpr.no_rawat = ?
            GROUP BY rjpr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan, jp.total_byrdr, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                rjdpr.no_rawat,
                jp.kd_jenis_prw AS kode_tindakan,
                jp.nm_perawatan AS nm_perawatan,
                COUNT(rjdpr.kd_jenis_prw) AS jml,
                jp.total_byrdrpr AS biaya,
                SUM(rjdpr.biaya_rawat) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM rawat_jl_drpr rjdpr
            JOIN jns_perawatan jp ON rjdpr.kd_jenis_prw = jp.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jp.kd_jenis_prw = rjt.kode_tindakan
            WHERE rjdpr.no_rawat = ?
            GROUP BY rjdpr.no_rawat, jp.kd_jenis_prw, jp.nm_perawatan, jp.total_byrdrpr, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                ridr.no_rawat,
                jpi.kd_jenis_prw AS kode_tindakan,
                jpi.nm_perawatan AS nm_perawatan,
                COUNT(ridr.kd_jenis_prw) AS jml,
                jpi.total_byrdr AS biaya,
                SUM(ridr.biaya_rawat) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM rawat_inap_dr ridr
            JOIN jns_perawatan_inap jpi ON ridr.kd_jenis_prw = jpi.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpi.kd_jenis_prw = rjt.kode_tindakan
            WHERE ridr.no_rawat = ?
            GROUP BY ridr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan, jpi.total_byrdr, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                ridpr.no_rawat,
                jpi.kd_jenis_prw AS kode_tindakan,
                jpi.nm_perawatan AS nm_perawatan,
                COUNT(ridpr.kd_jenis_prw) AS jml,
                jpi.total_byrdrpr AS biaya,
                SUM(ridpr.biaya_rawat) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM rawat_inap_drpr ridpr
            JOIN jns_perawatan_inap jpi ON ridpr.kd_jenis_prw = jpi.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpi.kd_jenis_prw = rjt.kode_tindakan
            WHERE ridpr.no_rawat = ?
            GROUP BY ridpr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan, jpi.total_byrdrpr, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                ripr.no_rawat,
                jpi.kd_jenis_prw AS kode_tindakan,
                jpi.nm_perawatan AS nm_perawatan,
                COUNT(ripr.kd_jenis_prw) AS jml,
                jpi.total_byrpr AS biaya,
                SUM(ripr.biaya_rawat) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM rawat_inap_pr ripr
            JOIN jns_perawatan_inap jpi ON ripr.kd_jenis_prw = jpi.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpi.kd_jenis_prw = rjt.kode_tindakan
            WHERE ripr.no_rawat = ?
            GROUP BY ripr.no_rawat, jpi.kd_jenis_prw, jpi.nm_perawatan, jpi.total_byrpr, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                pl.no_rawat,
                jpl.kd_jenis_prw AS kode_tindakan,
                jpl.nm_perawatan AS nm_perawatan,
                COUNT(pl.kd_jenis_prw) AS jml,
                pl.biaya AS biaya,
                SUM(pl.biaya) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM periksa_lab pl
            JOIN jns_perawatan_lab jpl ON pl.kd_jenis_prw = jpl.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpl.kd_jenis_prw = rjt.kode_tindakan
            WHERE pl.no_rawat = ?
            GROUP BY pl.no_rawat, jpl.kd_jenis_prw, jpl.nm_perawatan, pl.biaya, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                pr.no_rawat,
                jpr.kd_jenis_prw AS kode_tindakan,
                jpr.nm_perawatan AS nm_perawatan,
                COUNT(pr.kd_jenis_prw) AS jml,
                pr.biaya AS biaya,
                SUM(pr.biaya) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM periksa_radiologi pr
            JOIN jns_perawatan_radiologi jpr ON pr.kd_jenis_prw = jpr.kd_jenis_prw
            LEFT JOIN relasi_jenis_tindakan rjt ON jpr.kd_jenis_prw = rjt.kode_tindakan
            WHERE pr.no_rawat = ?
            GROUP BY pr.no_rawat, jpr.kd_jenis_prw, jpr.nm_perawatan, pr.biaya, rjt.jenis_tindakan

            UNION ALL

            SELECT 
                o.no_rawat,
                o.kode_paket AS kode_tindakan,
                p.nm_perawatan AS nm_perawatan,
                1 AS jml,
                (
                    IFNULL(p.operator1,0)+IFNULL(p.operator2,0)+IFNULL(p.operator3,0)+
                    IFNULL(p.asisten_operator1,0)+IFNULL(p.asisten_operator2,0)+IFNULL(p.asisten_operator3,0)+
                    IFNULL(p.instrumen,0)+IFNULL(p.dokter_anak,0)+IFNULL(p.perawaat_resusitas,0)+
                    IFNULL(p.dokter_anestesi,0)+IFNULL(p.asisten_anestesi,0)+IFNULL(p.asisten_anestesi2,0)+
                    IFNULL(p.bidan,0)+IFNULL(p.bidan2,0)+IFNULL(p.bidan3,0)+
                    IFNULL(p.perawat_luar,0)+IFNULL(p.sewa_ok,0)+IFNULL(p.alat,0)+
                    IFNULL(p.akomodasi,0)+IFNULL(p.bagian_rs,0)+IFNULL(p.omloop,0)+
                    IFNULL(p.omloop2,0)+IFNULL(p.omloop3,0)+IFNULL(p.omloop4,0)+
                    IFNULL(p.omloop5,0)+IFNULL(p.sarpras,0)+IFNULL(p.dokter_pjanak,0)+
                    IFNULL(p.dokter_umum,0)
                ) AS biaya,
                (
                    IFNULL(p.operator1,0)+IFNULL(p.operator2,0)+IFNULL(p.operator3,0)+
                    IFNULL(p.asisten_operator1,0)+IFNULL(p.asisten_operator2,0)+IFNULL(p.asisten_operator3,0)+
                    IFNULL(p.instrumen,0)+IFNULL(p.dokter_anak,0)+IFNULL(p.perawaat_resusitas,0)+
                    IFNULL(p.dokter_anestesi,0)+IFNULL(p.asisten_anestesi,0)+IFNULL(p.asisten_anestesi2,0)+
                    IFNULL(p.bidan,0)+IFNULL(p.bidan2,0)+IFNULL(p.bidan3,0)+
                    IFNULL(p.perawat_luar,0)+IFNULL(p.sewa_ok,0)+IFNULL(p.alat,0)+
                    IFNULL(p.akomodasi,0)+IFNULL(p.bagian_rs,0)+IFNULL(p.omloop,0)+
                    IFNULL(p.omloop2,0)+IFNULL(p.omloop3,0)+IFNULL(p.omloop4,0)+
                    IFNULL(p.omloop5,0)+IFNULL(p.sarpras,0)+IFNULL(p.dokter_pjanak,0)+
                    IFNULL(p.dokter_umum,0)
                ) AS total,
                COALESCE(rjt.jenis_tindakan, 'Tidak dikategorikan') AS jenis_tindakan
            FROM operasi o
            JOIN paket_operasi p ON o.kode_paket = p.kode_paket
            LEFT JOIN relasi_jenis_tindakan rjt ON p.kode_paket = rjt.kode_tindakan
            WHERE o.no_rawat = ?
            SQL;

        // Kita punya 9 SELECT dengan placeholder => butuh 9 binding values
        $bindings = array_fill(0, 9, $no_rawat);
        // Jalankan query dengan binding yang sesuai
        $rows = DB::select($sql, $bindings);

        // Ubah ke collection untuk mudah olah
        // ...
        $collection = collect($rows);

        // Group by jenis_tindakan tanpa membedakan huruf besar kecil
        $grouped = $collection
            ->groupBy(function ($item) {
                // normalize ke lowercase & hilangkan spasi berlebih
                return strtolower(trim($item->jenis_tindakan));
            })
            ->map(function ($items, $key) {
                // Hitung total biaya per jenis tindakan
                return (float) $items->sum(function ($it) {
                    return (float) ($it->total ?? 0);
                });
            });

        // ubah key-nya jadi kapital huruf pertama
        $formatted = $grouped->mapWithKeys(function ($value, $key) {
            return [ucfirst($key) => $value];
        });

        // Jika mau hasil akhir berupa array (bukan Collection)
        $rekap = $formatted->toArray();
        $obatbhpalkes = (array) $obatbhpalkes;

        // Ambil total biaya kamar dari tabel kamar_inap
        $totalKamar = DB::table('kamar_inap')
            ->where('no_rawat', $no_rawat)
            ->sum('ttl_biaya');

        // Pastikan nilai tidak null
        $totalKamar = $totalKamar ?? 0;

        // dd($rekap, $obatbhpalkes, $totalKamar);

        return view('inacbg.klaim', compact('pasien', 'sep', 'bayi', 'pemeriksaan', 'coder', 'obatbhpalkes', 'rekap', 'totalKamar'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // Ubah format tanggal jika ada
        if (!empty($input['tgl_masuk'])) {
            $input['tgl_masuk'] = Carbon::parse($input['tgl_masuk'])->format('Y-m-d H:i:s');
        }

        if (!empty($input['tgl_pulang'])) {
            $input['tgl_pulang'] = Carbon::parse($input['tgl_pulang'])->format('Y-m-d H:i:s');
        }

        // Cari log berdasarkan nomor_sep
        $log = LogEklaimRanap::updateOrCreate(
            ['nomor_sep' => $input['nomor_sep']], // kondisi unik
            [
                'nomor_kartu' => $input['nomor_kartu'] ?? null,
                'tgl_masuk' => $input['tgl_masuk'] ?? null,
                'tgl_pulang' => $input['tgl_pulang'] ?? null,
                'cara_masuk' => $input['cara_masuk'] ?? null,
                'tgl_masuk' => isset($input['tgl_masuk'])
                    ? Carbon::parse($input['tgl_masuk'])->format('Y-m-d H:i:s')
                    : null,
                'tgl_pulang' => isset($input['tgl_pulang'])
                    ? Carbon::parse($input['tgl_pulang'])->format('Y-m-d H:i:s')
                    : null,
                'discharge_status' => $input['discharge_status'] ?? null,
                'adl_sub_acute' => $input['adl_sub_acute'] ?? null,
                'adl_chronic' => $input['adl_chronic'] ?? null,
                'icu_indikator' => $input['icu_indikator'] ?? null,
                'icu_los' => $input['icu_los'] ?? null,
                'upgrade_class_ind' => $input['upgrade_class_ind'] ?? null,
                'upgrade_class_los' => $input['upgrade_class_los'] ?? null,
                'add_payment_pct' => $input['add_payment_pct'] ?? null,
                'birth_weight' => $input['birth_weight'] ?? null,
                'sistole' => $input['sistole'] ?? null,
                'diastole' => $input['diastole'] ?? null,
                'dializer_single_use' => $input['dializer_single_use'] ?? null,
                'tb_indikator' => $input['tb_indikator'] ?? null,
                'pemulasaraan_jenazah' => $input['pemulasaraan_jenazah'] ?? null,
                'kantong_jenazah' => $input['kantong_jenazah'] ?? null,
                'peti_jenazah' => $input['peti_jenazah'] ?? null,
                'desinfektan_jenazah' => $input['desinfektan_jenazah'] ?? null,
                'mobil_jenazah' => $input['mobil_jenazah'] ?? null,
                'desinfektan_mobil_jenazah' => $input['desinfektan_mobil_jenazah'] ?? null,
                'covid19_status_cd' => $input['covid19_status_cd'] ?? null,
                'nomor_kartu_t' => $input['nomor_kartu_t'] ?? null,
                'tarif_rs' => $input['tarif_rs'] ?? null,
                'episodes' => $input['episodes'] ?? null,
                'payor_id' => $input['payor_id'] ?? null,
                'payor_cd' => $input['payor_cd'] ?? null,
                'kode_tarif' => $input['kode_tarif'] ?? null,
                'coder_nik' => $input['coder_nik'] ?? null,
                'diagnosa_idrg' => null,
                'procedure_idrg' => null,
                'diagnosa_inacbg' => null,
                'procedure_inacbg' => null,
                'kelas_rawat' => $input['kelas_rawat'] ?? null,
                'jenis_rawat' => $input['jenis_rawat'] ?? null,
                'nomor_rm' => $input['nomor_rm'] ?? null,
                'nama_pasien' => $input['nama_pasien'] ?? null,
                'nama_dokter' => $input['nama_dokter'] ?? null,
                'response_set_claim_data' => $responseSet ?? null,
                'status' => isset($responseSet['metadata']['code']) && $responseSet['metadata']['code'] == 200
                    ? 'proses klaim'
                    : 'gagal'
            ]
        );

        // 2️⃣ Payload new_claim
        $payloadNew = [
            'nomor_sep' => $input['nomor_sep'],
            'nomor_kartu' => $input['nomor_kartu'],
            'nomor_rm' => $input['nomor_rm'],
            'nama_pasien' => $input['nama_pasien'],
            'tgl_lahir' => date('Y-m-d H:i:s', strtotime($input['tgl_lahir'])),
            'gender' => $input['gender'],
        ];

        $responseNew = $this->eklaim->send('new_claim', $payloadNew);
        $log->update(['response_new_claim' => $responseNew]);

        // 3️⃣ Payload set_claim_data
        $payloadSet = [
            'metadata' => [
                'method' => 'set_claim_data',
                'nomor_sep' => $payloadNew['nomor_sep'] ?? null, // pastikan SEP dari new_claim
            ],
            'data' => $input  // semua field Ranap yang relevan
        ];

        // Kirim ke e-Klaim
        $responseSet = $this->eklaim->send(
            'set_claim_data',
            $payloadSet['data'],      // data Ranap
            $payloadSet['metadata']   // metadata termasuk nomor_sep
        );

        // 4️⃣ Update log response set_claim_data + status
        $log->update([
            'response_set_claim_data' => $responseSet,
            'status' => 'proses klaim'
        ]);
        // 3️⃣ Tentukan status
        $status = (isset($responseSet['metadata']['code']) && $responseSet['metadata']['code'] == 200)
            ? 'proses klaim'
            : 'gagal';

        // 5️⃣ Redirect dengan alert
        if ($status === 'proses klaim') {
            // Jika gagal, panggil fungsi show agar ambil data dari query yang sama
            $requestShow = new Request(['no_rawat' => $input['no_rawat']]);
            return $this->show($requestShow)->with('success', 'Berhasil mengirim e-Klaim');
        } else {
            // Jika gagal, panggil fungsi show agar ambil data dari query yang sama
            $requestShow = new Request(['no_rawat' => $input['no_rawat']]);
            return $this->show($requestShow)->with('error', 'Gagal mengirim e-Klaim');
        }
    }

    public function hapusKlaim(Request $request)
    {
         $input = $request->all();

        $data = [
            'nomor_sep' => $request->nomor_sep,
            'coder_nik' => $request->coder_nik,
        ];

        $response = $this->eklaim->send('delete_claim', $data);

        // Hapus log berdasarkan nomor rawat
        LogEklaimRanap::where('nomor_sep', $request->nomor_sep)->delete();

        // Cek hasil respons dari e-Klaim
        $status = $response['metadata']['code'] ?? null;

        // Debug dulu jika perlu
        // dd($response);

        // 5️⃣ Redirect dengan alert (sama seperti function lain)
        if ($status == 200) {
            $requestShow = new Request(['no_rawat' => $input['no_rawat']]);
            return $this->show($requestShow)->with('success', 'Berhasil menghapus e-Klaim dan log terkait.');
        } else {
            $requestShow = new Request(['no_rawat' => $input['no_rawat']]);
            return $this->show($requestShow)->with('error', 'Gagal menghapus e-Klaim. Silakan cek koneksi atau respon server.');
        }
    }


}

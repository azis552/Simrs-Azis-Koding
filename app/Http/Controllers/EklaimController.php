<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EklaimService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EklaimController extends Controller
{
    protected EklaimService $eklaim;

    public function __construct(EklaimService $eklaim)
    {
        $this->eklaim = $eklaim;
    }

    /**
     * Membuat klaim baru
     */
    public function newClaim(Request $request)
    {
        // ✅ Validasi field wajib sesuai format e-Klaim
        $request->validate([
            'metadata.method' => 'required|in:new_claim',
            'data.nomor_sep' => 'required|string',
            'data.nomor_kartu' => 'required|string',
            'data.nomor_rm' => 'required|string',
            'data.nama_pasien' => 'required|string',
            'data.tgl_lahir' => 'required|date_format:Y-m-d H:i:s',
            'data.gender' => 'required|in:1,2',
        ]);

        // ✅ Ambil payload tanpa ubah struktur
        $payload = $request->all();
        $method = $payload['metadata']['method'] ?? 'new_claim';
        $extraMetadata = $payload['metadata'] ?? [];
        $data = $payload['data'] ?? [];

        // ✅ Kirim ke e-Klaim
        $result = $this->eklaim->send($method, $data, $extraMetadata);

        return response()->json($result);
    }
    public function updatePatient(Request $request)
    {
        $validated = $request->validate([
            'nomor_rm_lama' => 'required|string',   // RM lama (wajib di metadata)
            'nomor_kartu' => 'required|string',
            'nomor_rm' => 'required|string',
            'nama_pasien' => 'required|string',
            'tgl_lahir' => 'required|date_format:Y-m-d H:i:s',
            'gender' => 'required|in:1,2',
        ]);

        $data = [
            'nomor_kartu' => $validated['nomor_kartu'],
            'nomor_rm' => $validated['nomor_rm'],
            'nama_pasien' => $validated['nama_pasien'],
            'tgl_lahir' => $validated['tgl_lahir'],
            'gender' => $validated['gender'],
        ];

        // kirim metadata + data
        $result = $this->eklaim->send(
            'update_patient',
            $data,
            ['nomor_rm' => $validated['nomor_rm_lama']]
        );

        return response()->json($result);
    }

    public function deletePatient(Request $request)
    {
        $validated = $request->validate([
            'nomor_rm' => 'required|string',
            'coder_nik' => 'required|string',
        ]);

        $result = $this->eklaim->send('delete_patient', $validated);

        return response()->json($result);
    }
    /**
     * Set / update data klaim
     * (isi diagnosa, prosedur, tarif, dll)
     */
    public function setClaimData(Request $request)
    {
        // ✅ Validasi minimal tapi penting agar tidak gagal
        $request->validate([
            'metadata.method' => 'required|in:set_claim_data',
            'metadata.nomor_sep' => 'required|string',
            'data.nomor_sep' => 'required|string',
            'data.nomor_kartu' => 'required|string',
            'data.tgl_masuk' => 'required|date_format:Y-m-d H:i:s',
            'data.tgl_pulang' => 'required|date_format:Y-m-d H:i:s',
            'data.jenis_rawat' => 'required|in:1,2,3',
            'data.kelas_rawat' => 'required|in:1,2,3',
            'data.coder_nik' => 'required|string',
        ]);

        // ✅ Ambil payload utuh tanpa ubah
        $payload = $request->all();
        $method = $payload['metadata']['method'] ?? 'set_claim_data';
        $extraMetadata = $payload['metadata'] ?? [];
        $data = $payload['data'] ?? [];

        // ✅ Kirim ke e-Klaim
        $result = $this->eklaim->send($method, $data, $extraMetadata);

        return response()->json($result);
    }
    /**
     * IDRG Diagnosa Set
     * POST /api/idrg-diagnosa-set
     */
    public function idrgDiagnosaSet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:idrg_diagnosa_set',
            'metadata.nomor_sep' => 'required|string',
            'data.diagnosa' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        $result = $this->eklaim->send(
            $payload['metadata']['method'],
            $payload['data'],
            $payload['metadata']
        );

        return response()->json($result);
    }


    /**
     * IDRG Procedure Set
     * POST /api/idrg-procedure-set
     */
    public function idrgProcedureSet(Request $request)
    {
        // ✅ Validasi nested JSON sesuai struktur e-Klaim
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:idrg_procedure_set',
            'metadata.nomor_sep' => 'required|string',
            'data.procedure' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke service sesuai struktur e-Klaim
        $result = $this->eklaim->send(
            $payload['metadata']['method'],
            $payload['data'],
            $payload['metadata']
        );

        return response()->json($result);
    }

    public function idrgDiagnosaGet(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:idrg_diagnosa_get',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke e-Klaim service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // → idrg_diagnosa_get
            $payload['data'],                 // → data.nomor_sep
            $payload['metadata']              // → metadata
        );

        return response()->json($result);
    }

    public function idrgProcedureGet(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:idrg_procedure_get',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke e-Klaim service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // idrg_procedure_get
            $payload['data'],                 // data.nomor_sep
            $payload['metadata']              // metadata
        );

        return response()->json($result);
    }

    /**
     * Grouping IDRG
     * POST /api/grouping-idrg
     */
    public function groupingIdrg(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:grouper',
            'metadata.stage' => 'required|string|in:1',
            'metadata.grouper' => 'required|string|in:idrg',
            'data.nomor_sep' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke e-Klaim service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // grouper
            $payload['data'],                 // data.nomor_sep
            $payload['metadata']              // stage, grouper
        );

        Log::info('E-KLAIM GROUPING IDRG RESPONSE', ['result' => $result]);

        return response()->json($result);
    }
    public function finalIdrg(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:idrg_grouper_final',
            'data.nomor_sep' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke service e-Klaim
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // "idrg_grouper_final"
            $payload['data'],                 // { "nomor_sep": "UJICOBA5" }
            $payload['metadata']              // metadata.method
        );

        // ✅ Logging (opsional)
        Log::info('E-KLAIM FINAL IDRG RESPONSE', [
            'payload' => $payload,
            'result' => $result,
        ]);

        // ✅ Return hasil response dari WS
        return response()->json($result);
    }

    /**
     * 🔹 Re-Edit Grouping IDRG
     * Membuka kembali hasil grouping IDRG yang sudah difinal agar bisa diperbarui.
     */
    public function idrgGrouperReedit(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:idrg_grouper_reedit',
            'data.nomor_sep' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke e-Klaim service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // idrg_grouper_reedit
            $payload['data'],                 // data.nomor_sep
            $payload['metadata']              // metadata.method
        );

        // ✅ Logging
        Log::info('E-KLAIM IDRG GROUPEdit RESPONSE', [
            'payload' => $payload,
            'result' => $result,
        ]);

        // ✅ Return hasil
        return response()->json($result);
    }
    /**
     * 🔹 Import hasil Grouping IDRG ke INA-CBG
     * 
     * Mengirim hasil grouping IDRG agar bisa diproses lebih lanjut oleh sistem INA-CBG.
     */
    public function idrgToInacbgImport(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:idrg_to_inacbg_import',
            'data.nomor_sep' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke e-Klaim service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // idrg_to_inacbg_import
            $payload['data'],                 // { "nomor_sep": "UJICOBA5" }
            $payload['metadata']              // metadata
        );

        // ✅ Logging
        Log::info('E-KLAIM IDRG TO INACBG IMPORT RESPONSE', [
            'payload' => $payload,
            'result' => $result,
        ]);

        // ✅ Return hasil response
        return response()->json($result);
    }

    /**
     * 🔹 INA-CBG Diagnosa Set
     * POST /api/eklaim/inacbg-diagnosa-set
     */
    public function inacbgDiagnosaSet(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:inacbg_diagnosa_set',
            'metadata.nomor_sep' => 'required|string',
            'data.diagnosa' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke e-Klaim service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // inacbg_diagnosa_set
            $payload['data'],
            $payload['metadata']
        );

        return response()->json($result);
    }

    /**
     * 🔹 INA-CBG Procedure Set
     * POST /api/eklaim/inacbg-procedure-set
     */
    public function inacbgProcedureSet(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:inacbg_procedure_set',
            'metadata.nomor_sep' => 'required|string',
            'data.procedure' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // inacbg_procedure_set
            $payload['data'],
            $payload['metadata']
        );

        return response()->json($result);
    }

    /**
     * 🔹 INA-CBG Diagnosa Get
     * POST /api/eklaim/inacbg-diagnosa-get
     */
    public function inacbgDiagnosaGet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:inacbg_diagnosa_get',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // inacbg_diagnosa_get
            $payload['data'],
            $payload['metadata']
        );

        return response()->json($result);
    }

    /**
     * 🔹 INA-CBG Procedure Get
     * POST /api/eklaim/inacbg-procedure-get
     */
    public function inacbgProcedureGet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:inacbg_procedure_get',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // inacbg_procedure_get
            $payload['data'],
            $payload['metadata']
        );

        return response()->json($result);
    }

    /**
     * 🔹 Grouping INA-CBG Stage 1
     * POST /api/eklaim/grouping-inacbg-stage-1
     */
    public function groupingInacbgStage1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:grouper',
            'metadata.stage' => 'required|string|in:1',
            'metadata.grouper' => 'required|string|in:inacbg',
            'data.nomor_sep' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // grouper
            $payload['data'],                 // { nomor_sep }
            $payload['metadata']              // { stage:1, grouper:inacbg }
        );

        Log::info('E-KLAIM GROUPING INACBG STAGE 1 RESPONSE', ['result' => $result]);

        return response()->json($result);
    }

    /**
     * 🔹 Grouping INA-CBG Stage 2
     * POST /api/eklaim/grouping-inacbg-stage-2
     */
    public function groupingInacbgStage2(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:grouper',
            'metadata.stage' => 'required|string|in:2',
            'metadata.grouper' => 'required|string|in:inacbg',
            'data.nomor_sep' => 'required|string',
            'data.special_cmg' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke service e-Klaim
        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // grouper
            $payload['data'],                 // data.nomor_sep, special_cmg
            $payload['metadata']              // metadata
        );

        return response()->json($result);
    }


    /**
     * 🔹 Finalisasi Grouping INA-CBG
     * POST /api/eklaim/final-inacbg
     */
    public function finalInacbg(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:inacbg_grouper_final',
            'data.nomor_sep' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        $result = $this->eklaim->send(
            $payload['metadata']['method'],   // inacbg_grouper_final
            $payload['data'],
            $payload['metadata']
        );

        Log::info('E-KLAIM FINAL INACBG RESPONSE', ['result' => $result]);

        return response()->json($result);
    }

    public function groupingInacbgReedit(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:inacbg_grouper_reedit',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim request ke e-Klaim Service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],  // inacbg_grouper_reedit
            $payload['data'],                // data.nomor_sep
            $payload['metadata']             // metadata
        );

        return response()->json($result);
    }

    public function claimFinal(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:claim_final',
            'data.nomor_sep' => 'required|string',
            'data.coder_nik' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim request ke e-Klaim Service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],  // claim_final
            $payload['data'],                // data.nomor_sep, coder_nik
            $payload['metadata']             // metadata
        );

        return response()->json($result);
    }

    public function reeditClaim(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:reedit_claim',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim request ke e-Klaim Service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],  // reedit_claim
            $payload['data'],                // data.nomor_sep
            $payload['metadata']             // metadata
        );

        return response()->json($result);
    }





    /**
     * Kirim klaim ke data center (individual)
     */
    public function claimSend(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:send_claim_individual',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim ke e-Klaim service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],  // send_claim_individual
            $payload['data'],                // data.nomor_sep
            $payload['metadata']             // metadata
        );

        return response()->json($result);
    }


    /**
     * Ambil data klaim detail
     */
    public function getClaimData(Request $request)
    {
        // ✅ Validasi nested JSON
        $validator = Validator::make($request->all(), [
            'metadata.method' => 'required|string|in:get_claim_data',
            'data.nomor_sep' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = $validator->validated();

        // ✅ Kirim request ke e-Klaim Service
        $result = $this->eklaim->send(
            $payload['metadata']['method'],  // get_claim_data
            $payload['data'],                // data.nomor_sep
            $payload['metadata']             // metadata
        );

        return response()->json($result);
    }

    public function claimPrint(Request $request)
{
    $validator = Validator::make($request->all(), [
        'metadata.method' => 'required|string|in:claim_print,claim_print_individual',
        'data.nomor_sep' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors' => $validator->errors(),
        ], 422);
    }

    $payload = $validator->validated();

    // ✅ Kirim request ke e-Klaim
    $result = $this->eklaim->send(
        $payload['metadata']['method'], 
        $payload['data'], 
        $payload['metadata']
    );

    // Kalau hasilnya base64 PDF
    if (isset($result['response']['file']) && !empty($result['response']['file'])) {
        $pdfData = base64_decode($result['response']['file']);
        $filename = 'claim_' . $payload['data']['nomor_sep'] . '.pdf';
        return response($pdfData)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    return response()->json($result);
}






}

<?php

use App\Http\Controllers\EklaimController;
use App\Http\Controllers\IcdController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Route::prefix('eklaim')->group(function () {
//     Route::post('/new-claim', [EklaimController::class, 'newClaim']);
//     Route::post('/update-patient', [EklaimController::class, 'updatePatient']);
//     Route::post('/delete-patient', [EklaimController::class, 'deletePatient']);
//     Route::post('/set-claim-data', [EklaimController::class, 'setClaimData']);
//     Route::post('/inacbg-diagnosa-set', [EklaimController::class, 'inacbgDiagnosaSet']);
//     Route::post('/inacbg-procedure-set', [EklaimController::class, 'inacbgProcedureSet']);
//     Route::post('/grouper1', [EklaimController::class, 'grouper1']);
//     Route::post('/grouper2', [EklaimController::class, 'grouper2']);
//     Route::post('/claim-final', [EklaimController::class, 'claimFinal']);
//     Route::post('/send-claim-individual', [EklaimController::class, 'sendClaimIndividual']);
//     Route::post('/get-claim-data', [EklaimController::class, 'getClaimData']);
// });

Route::prefix('eklaim')->group(function () {
    Route::post('/new-claim', [EklaimController::class, 'newClaim']);
    Route::post('/set-claim-data', [EklaimController::class, 'setClaimData']);
    Route::post('/idrg-diagnosa-set', [EklaimController::class, 'idrgDiagnosaSet']);
    Route::post('/idrg-diagnosa-get', [EklaimController::class, 'idrgDiagnosaGet']);
    Route::post('/idrg-procedure-set', [EklaimController::class, 'idrgProcedureSet']);
    Route::post('/idrg-procedure-get', [EklaimController::class, 'idrgProcedureGet']);
    Route::post('/grouping-idrg', [EklaimController::class, 'groupingIdrg']);
    Route::post('/final-idrg', [EklaimController::class, 'finalIdrg']);
    Route::post('/idrg-grouper-reedit', [EklaimController::class, 'idrgGrouperReedit']);
    Route::post('/idrg-to-inacbg-import', [EklaimController::class, 'idrgToInacbgImport']);
    Route::post('/inacbg-diagnosa-set', [EklaimController::class, 'inacbgDiagnosaSet']);
    Route::post('/inacbg-procedure-set', [EklaimController::class, 'inacbgProcedureSet']);
    Route::post('/inacbg-diagnosa-get', [EklaimController::class, 'inacbgDiagnosaGet']);
    Route::post('/inacbg-procedure-get', [EklaimController::class, 'inacbgProcedureGet']);
    Route::post('/grouping-inacbg-stage-1', [EklaimController::class, 'groupingInacbgStage1']);
    Route::post('/grouping-inacbg-stage-2', [EklaimController::class, 'groupingInacbgStage2']);
    Route::post('/final-inacbg', [EklaimController::class, 'finalInacbg']);
    Route::post('/grouping-inacbg-reedit', [EklaimController::class, 'groupingInacbgReedit']);
    Route::post('/claim-final', [EklaimController::class, 'claimFinal']);
    Route::post('/reedit-claim', [EklaimController::class, 'reeditClaim']);
    Route::post('/claim-send', [EklaimController::class, 'claimSend']);
    Route::post('/get-claim-data', [EklaimController::class, 'getClaimData']);
    Route::post('/claim-print', [EklaimController::class, 'claimPrint']);


});
Route::get('/icd10', [IcdController::class, 'icd10']);
Route::get('/icd9', [IcdController::class, 'icd9']);

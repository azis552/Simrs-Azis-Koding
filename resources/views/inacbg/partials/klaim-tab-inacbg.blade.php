{{-- resources/views/inacbg/partials/klaim-tab-inacbg.blade.php --}}
@php
    $hasFinal     = !empty(optional($log)->response_inacbg_final);
    $stage1Data   = json_decode(optional($log)->response_inacbg_stage1, true);
    $cbgCode      = $stage1Data['response_inacbg']['cbg']['code'] ?? '';
    $cbgFirstChar = strtoupper(substr($cbgCode, 0, 1));
@endphp

<div class="tab-pane fade active show" id="inacbgimport" role="tabpanel">
    <div class="row">
        <div class="col-12 text-center mb-3">
            <button id="btnImportInacbg"
                {{ optional($log)->response_inacbg_final != null ? 'disabled' : '' }}
                class="btn btn-primary btn-lg">
                <i class="fas fa-exchange-alt"></i> Import iDRG → INA-CBG
            </button>
        </div>

        {{-- Diagnosa INACBG --}}
        <div class="col-md-6">
            <div class="card"><div class="card-body">
                <h5>Diagnosa INACBG (ICD-10)</h5>
                <select id="diagnosa_inacbg" class="form-control"
                    {{ optional($log)->response_inacbg_final != null ? 'disabled' : '' }}></select>
                <div class="table-responsive mt-2">
                    <table id="tabel_diagnosa_inacbg" class="table table-bordered table-sm">
                        <thead><tr><th>#</th><th>Kode</th><th>Deskripsi</th><th>Status</th><th>Hapus</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button class="btn btn-sm mt-2 {{ optional($log)->response_inacbg_final != null ? 'btn-disabled' : 'btn-primary' }}"
                    id="diagnosa-inacbg-simpan"
                    {{ optional($log)->response_inacbg_final != null ? 'disabled' : '' }}>Simpan</button>
            </div></div>
        </div>

        {{-- Prosedur INACBG --}}
        <div class="col-md-6">
            <div class="card"><div class="card-body">
                <h5>Prosedur INACBG (ICD-9-CM)</h5>
                <select id="prosedur_inacbg" class="form-control"
                    {{ optional($log)->response_inacbg_final != null ? 'disabled' : '' }}></select>
                <div class="table-responsive mt-2">
                    <table id="tabel_prosedur_inacbg" class="table table-bordered table-sm">
                        <thead><tr><th>#</th><th>Kode</th><th>Deskripsi</th><th>Status</th><th>Hapus</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button class="btn btn-sm mt-2 {{ optional($log)->response_inacbg_final != null ? 'btn-disabled' : 'btn-primary' }}"
                    id="prosedur-inacbg-simpan"
                    {{ optional($log)->response_inacbg_final != null ? 'disabled' : '' }}>Simpan</button>
            </div></div>
        </div>

        <div id="hasil_import_inacbg" class="mt-3 col-12"></div>
    </div>

    @if(optional($log)->response_inacbg_import != null || optional($log)->procedure_inacbg != null)
        <button class="btn btn-primary {{ optional($log)->response_inacbg_final != null ? 'btn-disabled' : '' }}"
            {{ optional($log)->response_inacbg_final != null ? 'disabled' : '' }}
            id="btnGroupingInacbg">Grouping INA-CBG</button>
        <div id="groupingInacbgResult" class="mt-3"></div>

        @if($stage1Data)
            <div class="mt-3">
                @if($cbgFirstChar !== 'X')
                    @if($hasFinal)
                        <p>{{ optional($log)->response_claim_final }}</p>
                        <div class="mt-3 text-center">
                            @if(optional($log)->response_claim_final == null)
                                <button class="btn btn-warning" id="btnReeditInacbg">
                                    <i class="bi bi-arrow-repeat"></i> Re-edit INA-CBG
                                </button>
                                <button id="btnClaimFinal" class="btn btn-success">
                                    <i class="fas fa-flag-checkered"></i> Claim Final INA-CBG
                                </button>
                            @endif
                            @if(!empty(optional($log)->response_claim_final))
                                @if(empty(optional($log)->response_send_claim_individual))
                                    <button id="btnReeditClaim" class="btn btn-warning">
                                        <i class="fa fa-refresh"></i> Re-edit Claim
                                    </button>
                                @endif
                                <button id="btnSendClaim" class="btn btn-success" style="display:none;">
                                    <i class="fa fa-paper-plane"></i> Kirim Claim Individual
                                </button>
                                <button class="btn btn-danger" id="btnPrintClaim"
                                    data-sep="{{ optional($log)->nomor_sep }}">
                                    🖨️ Print Claim
                                </button>
                            @endif
                        </div>
                        <div id="cardClaimFinal" class="card mt-4" style="display:none;">
                            <div class="card-header bg-success text-white"><strong>Hasil Claim Final INA-CBG</strong></div>
                            <div class="card-body">
                                <pre id="resultClaimFinal" class="bg-light p-3 rounded" style="max-height:400px;overflow-y:auto;"></pre>
                            </div>
                        </div>
                    @else
                        <button id="btnFinalInacbg" class="btn btn-warning"
                            data-nomor-sep="{{ optional($log)->nomor_sep }}">
                            Grouping Final INA-CBG
                        </button>
                    @endif
                @else
                    <p class="text-danger">Final tidak tersedia untuk code diawali "{{ $cbgFirstChar }}"</p>
                @endif
            </div>
        @endif
    @endif
</div>

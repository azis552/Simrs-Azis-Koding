{{-- resources/views/inacbg/partials/klaim-tab-idrg.blade.php --}}
@php
    $response      = json_decode(optional($log)->response_grouping_idrg ?? '{}', true);
    $response_idrg = $response['response_idrg'] ?? null;
    $final         = json_decode(optional($log)->response_idrg_grouper_final ?? '{}', true);
@endphp

<div class="tab-pane fade {{ optional($log)->status == 'proses klaim' ? 'active show' : '' }}" id="diagnosa" role="tabpanel">
    <div class="row">
        {{-- Diagnosa IDRG --}}
        <div class="col-md-6">
            <div class="card"><div class="card-body">
                <h5>Diagnosa IDRG (ICD-10)</h5>
                <select id="diagnosa_idrg" class="form-control"
                    {{ optional($log)->response_idrg_grouper_final != null ? 'disabled' : '' }}></select>
                <div class="table-responsive mt-2">
                    <table id="tabel_diagnosa" class="table table-bordered table-sm">
                        <thead><tr><th>#</th><th>Kode</th><th>Deskripsi</th><th>Status</th><th>Hapus</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button class="btn btn-sm mt-2 {{ optional($log)->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }}"
                    id="diagnosa-idrg-simpan"
                    {{ optional($log)->response_idrg_grouper_final != null ? 'disabled' : '' }}>Simpan</button>
            </div></div>
        </div>

        {{-- Prosedur IDRG --}}
        <div class="col-md-6">
            <div class="card"><div class="card-body">
                <h5>Prosedur IDRG (ICD-9-CM)</h5>
                <select id="prosedur_idrg" class="form-control"
                    {{ optional($log)->response_idrg_grouper_final != null ? 'disabled' : '' }}></select>
                <div class="table-responsive mt-2">
                    <table id="tabel_prosedur" class="table table-bordered table-sm">
                        <thead><tr><th>#</th><th>Kode</th><th>Deskripsi</th><th>Qty</th><th>Status</th><th>Hapus</th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button class="btn btn-sm mt-2 {{ optional($log)->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }}"
                    id="prosedur-idrg-simpan"
                    {{ optional($log)->response_idrg_grouper_final != null ? 'disabled' : '' }}>Simpan</button>
            </div></div>
        </div>

        <button id="btnGroupingIdrg"
            class="btn {{ optional($log)->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }}"
            {{ optional($log)->response_idrg_grouper_final != null ? 'disabled' : '' }}>
            Proses Grouping iDRG
        </button>
    </div>

    {{-- Hasil Grouping --}}
    @if($response_idrg)
        <table class="table table-bordered mt-3">
            <thead class="text-center"><tr><th colspan="3">Hasil Grouping iDRG</th></tr></thead>
            <tbody>
                <tr><td><b>MDC</b></td><td>{{ $response_idrg['mdc_description'] ?? '-' }}</td><td class="text-center">{{ $response_idrg['mdc_number'] ?? '-' }}</td></tr>
                <tr><td><b>DRG</b></td><td>{{ $response_idrg['drg_description'] ?? '-' }}</td><td class="text-center">{{ $response_idrg['drg_code'] ?? '-' }}</td></tr>
                <tr><td><b>Cost Weight</b></td><td colspan="2">{{ $response_idrg['cost_weight'] ?? '0.00' }}</td></tr>
                <tr><td><b>NBR</b></td><td colspan="2">{{ $response_idrg['nbr'] ?? '-' }}</td></tr>
                <tr><td><b>Status</b></td><td colspan="2">{{ optional($log)->response_idrg_grouper_final != null ? 'Final' : ($response_idrg['status_cd'] ?? '-') }}</td></tr>
            </tbody>
        </table>

        @if($response_idrg['mdc_number'] != '36')
            <button id="btnFinalIdrg"
                class="btn mt-2 {{ optional($log)->response_idrg_grouper_final != null ? 'btn-disabled' : 'btn-primary' }}"
                {{ optional($log)->response_grouping_idrg == null ? 'disabled' : '' }}>
                ✔ Final IDRG
            </button>
        @else
            <div class="alert alert-danger mt-2">⚠️ Tidak dapat memfinalkan karena kode MDC 36 (Ungroupable or Unrelated)</div>
        @endif
    @else
        <div class="alert alert-warning mt-3">Belum ada hasil grouping iDRG tersimpan</div>
    @endif

    {{-- Hasil Final IDRG --}}
    <div id="hasil_final_idrg" class="mt-4">
        @if(!empty($final))
            <div class="alert alert-success"><b>Final IDRG</b></div>
            @if(empty(optional($log)->response_send_claim_individual))
                <button id="btnReeditIdrg" class="btn btn-warning">✎ Re-edit iDRG</button>
            @endif
        @endif
    </div>
</div>

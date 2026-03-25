@extends('template.master')

@section('content')
<style>
.table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
@media (max-width: 768px) {
    table.table-sm th, table.table-sm td { font-size: 13px; white-space: nowrap; }
    .card-body { padding: 10px; }
    select.form-control { font-size: 14px; }
}
.select2-container .select2-selection--single {
    background-color: white !important; color: black !important;
    border: 1px solid black !important; border-radius: 4px; height: 38px;
}
.select2-container .select2-dropdown { background-color: white !important; color: black !important; border: 1px solid black !important; border-radius: 4px; }
.select2-container .select2-results__option { color: black !important; }
.select2-container .select2-selection__placeholder { color: black !important; font-weight: normal !important; }
.select2-container .select2-selection__rendered { color: black !important; background-color: white !important; }
</style>

{{-- Data klaim state untuk JS --}}
<script>
    window.isFinalIdrg   = {{ optional($log)->response_idrg_grouper_final  !== null ? 'true' : 'false' }};
    window.isFinalInacbg = {{ optional($log)->response_inacbg_final !== null ? 'true' : 'false' }};
    window.nomorSep      = "{{ optional($log)->nomor_sep }}";
    window.coderNik      = "{{ optional($log)->coder_nik }}";
    window.baseUrl       = "{{ url('') }}";
    window.csrfToken     = "{{ csrf_token() }}";
    window.logDiagnosaIdrg    = @json($log->diagnosa_idrg    ?? null);
    window.logProsedurIdrg    = @json($log->procedure_idrg   ?? null);
    window.logDiagnosaInacbg  = @json($log->diagnosa_inacbg  ?? null);
    window.logProsedurInacbg  = @json($log->procedure_inacbg ?? null);
    window.existingStage1     = @json($log->response_inacbg_stage1 ?? null);
    window.existingStage2     = @json($log->response_inacbg_stage2 ?? null);
</script>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">
                            <div class="card">
                                <div class="card-body">
                                    <h4>E-Klaim</h4>
                                    <h5 class="mb-3">[{{ $pasien->no_rkm_medis }}] {{ $pasien->nm_pasien }}</h5>

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    {{-- Data Pasien --}}
                                    @include('inacbg.partials.klaim-pasien-info', compact('pasien'))

                                    {{-- Tabs --}}
                                    @include('inacbg.partials.klaim-tabs', compact('log'))

                                    {{-- Tab Content --}}
                                    <div class="tab-content">
                                        {{-- Tab 1: Data Klaim --}}
                                        @include('inacbg.partials.klaim-tab-data', compact('log','pasien','sep','bayi','pemeriksaan','rekap','obatbhpalkes','totalKamar','coder'))

                                        {{-- Tab 2: Diagnosa & Prosedur IDRG --}}
                                        @include('inacbg.partials.klaim-tab-idrg', compact('log'))

                                        {{-- Tab 3: Import INA-CBG (kondisional) --}}
                                        @if(optional($log)->status == 'proses final idrg')
                                            @include('inacbg.partials.klaim-tab-inacbg', compact('log'))
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="styleSelector"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    {{-- Semua JS dipisah ke public/js/eklaim/ --}}
    <script src="{{ asset('js/eklaim/rupiah-formatter.js') }}"></script>
    <script src="{{ asset('js/eklaim/idrg-diagnosa-prosedur.js') }}"></script>
    <script src="{{ asset('js/eklaim/inacbg-diagnosa-prosedur.js') }}"></script>
    <script src="{{ asset('js/eklaim/grouping-inacbg.js') }}"></script>
    <script src="{{ asset('js/eklaim/klaim-actions.js') }}"></script>
@endsection

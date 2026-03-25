{{-- resources/views/inacbg/partials/klaim-tab-data.blade.php --}}
@php $isReadonly = !empty($log); @endphp

<div class="tab-pane fade {{ optional($log)->id == null ? 'show active' : '' }}" id="dataKlaim" role="tabpanel">
    <h3 class="mb-4">Form Klaim E-Klaim (Set Claim Data)</h3>

    @if($isReadonly)
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('#form-claim input, #form-claim select, #form-claim textarea').forEach(function (el) {
                el.tagName === 'SELECT' ? el.setAttribute('disabled', true) : el.setAttribute('readonly', true);
            });
        });
        </script>
    @endif

    <form action="{{ route('inacbg-ranap.store') }}" id="form-claim" method="POST">
        @csrf
        <input type="hidden" name="no_rawat"     value="{{ $pasien->no_rawat }}">
        <input type="hidden" name="nomor_rm"     value="{{ $pasien->no_rkm_medis }}">
        <input type="hidden" name="nama_pasien"  value="{{ $pasien->nm_pasien }}">
        <input type="hidden" name="gender"       value="{{ $pasien->jk == 'L' ? '1' : '2' }}">
        <input type="hidden" name="tgl_lahir"    value="{{ $pasien->tgl_lahir }}">

        {{-- Data Utama --}}
        @include('inacbg.partials.klaim-form-utama', compact('log','sep','pasien'))

        {{-- Data Rawat --}}
        @include('inacbg.partials.klaim-form-rawat', compact('log','sep','pasien'))

        {{-- Data Tambahan --}}
        @include('inacbg.partials.klaim-form-tambahan', compact('log','sep','bayi','pemeriksaan'))

        {{-- Tarif RS --}}
        @include('inacbg.partials.klaim-form-tarif', compact('log','rekap','obatbhpalkes','totalKamar'))

        {{-- Lain-lain --}}
        @include('inacbg.partials.klaim-form-lainlain', compact('log','coder'))

        @if($isReadonly)
            <div class="alert alert-info mt-4">Data klaim sudah dikirim, tidak dapat diubah.</div>
        @else
            <div class="mt-4">
                <button type="submit" class="btn btn-pr">Simpan Data Klaim</button>
            </div>
        @endif
    </form>

    @if($isReadonly)
        <form action="{{ route('inacbg.hapusklaim') }}" method="POST" class="mt-2">
            @csrf
            <input type="hidden" name="no_rawat"   value="{{ $pasien->no_rawat }}">
            <input type="hidden" name="nomor_sep"  value="{{ optional($log)->nomor_sep }}">
            <input type="hidden" name="coder_nik"  value="{{ optional($log)->coder_nik }}">
            <button type="submit" class="btn btn-danger">Hapus Klaim</button>
        </form>
    @endif
</div>

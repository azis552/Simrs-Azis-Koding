{{-- resources/views/inacbg/partials/klaim-tabs.blade.php --}}
<ul class="nav nav-tabs mb-3" id="klaimTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ optional($log)->id == null ? 'active' : '' }}"
           data-bs-toggle="tab" href="#dataKlaim" role="tab">Data Klaim</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ optional($log)->status == 'proses klaim' ? 'active' : '' }}"
           data-bs-toggle="tab" href="#diagnosa" role="tab">Diagnosa & Prosedur IDRG</a>
    </li>
    @if(optional($log)->status == 'proses final idrg')
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#inacbgimport" role="tab">
                Import INA-CBG
            </a>
        </li>
    @endif
</ul>

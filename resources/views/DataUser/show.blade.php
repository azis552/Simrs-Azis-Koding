@extends('template.master')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">

                    <div class="page-body">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="sub-title">Detail User</h4>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ $user->user_name }}">
                                    </div>
                                </div>

                                <h3>Mapping Pegawai</h3>
                                <form action="{{ route('users.mapping') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_users" value="{{ $user->user_id }}">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Pegawai</label>
                                        <div class="col-sm-10">
                                            <select name="id_pegawai" id="pegawai" class="form-select">
                                                @foreach ($pegawai as $p)
                                                    <option value="{{ $p->id }}"
                                                        {{ $user->pegawai_id == $p->id ? 'selected' : '' }}>
                                                        {{ $p->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-secondary">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="styleSelector">

                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('script')
        <script>
            $(document).ready(function() {
                $('#pegawai').select2({
                    theme: 'bootstrap-5',
                    placeholder: "Pilih Pegawai",
                    allowClear: true
                });

            });
        </script>
    @endsection

@extends('backend.v_layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12"> <!-- Atur lebar card di berbagai layar -->
            <div class="card">
                <div class="card-header">
                    <h3><b>{{ $judul }}</b></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto</label>
                                @if ($user->foto)
                                    <img src="{{ asset('storage/img-user/' . $user->foto) }}" class="foto-preview" width="100%">
                                @else
                                    <img src="{{ asset('Backend/Gambar/img-default.jpg') }}" class="foto-preview" width="100%">
                                @endif
                            </div>
                        </div>
        
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Role</label>
                                <select disabled name="role" class="form-control">
                                    <option value="" {{ old('role', $user->role) == '' ? 'selected' : '' }}>- Pilih Role -</option>
                                    <option value="0" {{ old('role', $user->role) == '0' ? 'selected' : '' }}>Admin</option>
                                    <option value="1" {{ old('role', $user->role) == '1' ? 'selected' : '' }}>Kasir</option>
                                </select>
                            </div>
        
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" disabled name="nama" value="{{ old('nama', $user->nama) }}" class="form-control">
                            </div>
        
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" disabled name="email" value="{{ old('email', $user->email) }}" class="form-control">
                            </div>
        
                            <div class="form-group">
                                <label>HP</label>
                                <input type="text" disabled name="hp" value="{{ old('hp', $user->hp) }}" class="form-control">
                            </div>
        
                        </div>
                    </div>
        
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <a href="{{ url()->previous() }}">
                                <button type="button" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
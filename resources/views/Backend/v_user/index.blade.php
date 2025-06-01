@extends('backend.v_layouts.app')
@section('content')

<div class="col-12">
      <div class="card mt-3">
        <div class="card-header">
          <h3><b>{{ $judul }}</b></h3>
        </div>
        <div class="card-body">
          <div class="col d-flex justify-content-end">
            <a href="#">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal"><i class="fas fa-plus"></i> Tambah</button>
            </a>
          </div>
          <div class="table-responsive table-hover mt-3">
            <table
              id="dataTable"
              class="table table-striped table-bordered"
            >
              <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No.HP</th>
                    <th>Role</th>
                    <th style="white-space: nowrap;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($index as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          @if ($row->foto)
                            <img src="{{ asset('storage/img-user/' . $row->foto) }}" width="50px" height="60px" alt="Foto Pengguna">
                          @else
                            <img src="{{ asset('Backend/Gambar/img-default.jpg') }}" width="50px" alt="Foto Pengguna">
                          @endif
                        </td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->hp }}</td>
                        <td>
                            @if ($row->role == 1)
                                <span class="badge bg-warning" style="color: white">Operator</span>    
                            @elseif ($row->role == 0)
                                <span class="badge bg-success" style="color: white">Admin</span>    
                            @elseif ($row->role == 3)
                                <span class="badge bg-success" style="color: white">Owner</span>    
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="#">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $row->id }}">
                                  <i class="far fa-edit"></i> Edit</button>
                            </a>

                            <form action="{{ route('backend.user.destroy', $row->id) }}" method="POST" style="display: inline-block">
                                @method('delete')
                                @csrf

                                <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama }}"><i class="fas fa-trash"> Hapus</i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
</div>

{{-- Modal Tambah User--}}
<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal-lg untuk lebar besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahUserModalLabel"><b>Tambah Pengguna</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('backend.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto</label>
                                <img class="foto-preview mb-2" style="max-width: 100%;">
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
                                @error('foto')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value="" {{ old('role') == '' ? 'selected' : '' }}>- Pilih Role -</option>
                                    <option value="0" {{ old('role') == '0' ? 'selected' : '' }}>Admin</option>
                                    <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Operator</option>
                                    <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>Owner</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                                @error('nama')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email">
                                @error('email')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>HP</label>
                                <input type="text" name="hp" value="{{ old('hp') }}" class="form-control @error('hp') is-invalid @enderror" placeholder="Masukkan Nomor HP" onkeypress="return hanyaAngka(event)">
                                @error('hp')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
                                @error('password')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Masukkan Konfirmasi Password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit User --}}
@foreach ($index as $row)
    <div class="modal fade" id="editUserModal{{ $row->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel{{ $row->id }}"><b>Edit Pengguna</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('backend.user.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>
                                    @if ($row->foto)
                                        <img src="{{ asset('storage/img-user/' . $row->foto) }}" class="foto-preview mb-2" width="100%">
                                    @else
                                        <img src="{{ asset('Backend/Gambar/img-default.jpg') }}" class="foto-preview mb-2" width="100%">
                                    @endif

                                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
                                    @error('foto')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="" {{ old('role', $row->role) == '' ? 'selected' : '' }}>- Pilih Role -</option>
                                        <option value="0" {{ old('role', $row->role) == '0' ? 'selected' : '' }}>Admin</option>
                                        <option value="1" {{ old('role', $row->role) == '1' ? 'selected' : '' }}>Kasir</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" value="{{ old('nama', $row->nama) }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                                    @error('nama')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" value="{{ old('email', $row->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email">
                                    @error('email')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>HP</label>
                                    <input type="text" name="hp" value="{{ old('hp', $row->hp) }}" class="form-control @error('hp') is-invalid @enderror" placeholder="Masukkan Nomor HP" onkeypress="return hanyaAngka(event)">
                                    @error('hp')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbaharui</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
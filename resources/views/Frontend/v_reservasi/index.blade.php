@extends('frontend.v_layout.app')
@section('content')

<style>
    .hero-section {
        position: relative;
        background-image: linear-gradient(to bottom, rgba(44, 62, 80, 0.7) 0%, rgba(44, 62, 80, 0.7) 100%),url('frontend/assets/img/bg3.jfif');
        background-size: cover;
        background-position: center;
        height: 40vh;
        clip-path: ellipse(100% 90% at 50% 0%); /* Membuat lengkung keluar bawah */
    }

    .hero-section .overlay {
        background: rgba(0, 0, 0, 0.5); /* gelapkan background agar teks terlihat */
        z-index: 1;
    }

    .hero-section .container {
        z-index: 2;
        padding-top: 10vh;
    }
    .btn-light:hover {
        background-color: var(--bs-primary);
        color: white;
        border-color: var(--bs-primary);
        transition: background-color 0.2s ease;
    }
    .custom-btn-group .btn {
        height: 60px;
        font-weight: bold;
        border: none;
        font-size: 1rem;
        width: 50%;
    }

    .custom-btn-group .btn:focus {
        box-shadow: none;
    }
    .custom-btn-group {
        border-radius: 30px;
        overflow: hidden;
    }
</style>

<div class="hero-section d-flex align-items-center justify-content-center text-center">
    <div class="overlay w-100 h-100 position-absolute top-0 start-0"></div>

    <div class="container position-relative">
        <div class="d-flex align-items-center justify-content-center position-relative">
            <a href="{{ route('beranda') }}" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center position-absolute start-0"
               style="width: 40px; height: 40px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="text-white fw-semibold mb-0">Add Reservation</h3>
        </div>
        <hr class="divider my-3" />
    </div>
    
</div>

<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-lg-8 col-xl-6 text-center">
            <p class="text-muted mb-5">
                Reservasi sekarang dan pastikan meja favorit Anda tersedia saat datang! 🎱
            </p>
        </div>
    </div>
    <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
        <div class="col-lg-6">
            <form id="form-tambah" action="{{ route('reserved') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Table input-->
                <fieldset id="step1">
                    <legend>Informasi Meja</legend>
                    <div class="form-floating mb-3">
                        <select name="meja_id" id="meja_id" class="form-select @error('meja_id') is-invalid @enderror" required>
                            <option value="" {{ old('meja_id') == '' ? 'selected' : '' }}>- Pilih Meja -</option>
                            @foreach ($index as $row)
                            <option value="{{ $row->id }}" data-harga="{{ $row->kategori->harga }}">
                                Table {{ $row->nomor_meja }} - {{ $row->kategori->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        @error('meja_id')
                            <div class="invalid-feedback alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                        <input type="hidden" name="meja_id" id="meja_id_hidden">
                        <label for="meja_id">Table</label>
                        <div class="invalid-feedback">Table wajib diisi.</div>
                    </div>
                    <!-- Date input-->
                    <div class="form-floating mb-3">
                        <input class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" type="datetime-local" placeholder="" required />
                        <input type="hidden" name="jam_mulai" id="jam_mulai_hidden">
                        <label for="jam_mulai">Jam Mulai</label>
                        <div class="invalid-feedback">Jam Mulai wajib diisi.</div>
                    </div>
                    <!-- Duration input-->
                    <div class="form-floating mb-3">
                        <input class="form-control @error('durasi') is-invalid @enderror" id="durasi" type="number" step="1" min="1" placeholder="1" required />
                        <input type="hidden" name="durasi" id="durasi_hidden">
                        <label for="durasi">Durasi (/Jam)</label>
                        <div class="invalid-feedback">Durasi wajib diisi.</div>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-xl" onclick="goToStep2()">Next</button>
                    </div>
                </fieldset>
                <fieldset id="step2" style="display:none; margin-top: 30px;">
                    <legend>Pembayaran</legend>
                    <!-- Customer Name-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="nama_pelanggan" type="text" placeholder="" value="{{ Auth::user()->nama }}" readonly />
                        <label for="nama_pelanggan">Nama</label>
                    </div>
                    <!-- Total-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="total" type="text" placeholder="" value="" readonly />
                        <input type="hidden" name="total_bayar" id="total_bayar">
                        <label for="total">Total</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror" required>
                            <option value="" selected> - Pilih Metode Bayar - </option>
                            <option value="0" {{ old('metode_bayar', $row->metode_bayar) == '0' ? 'selected' : '' }}>Cash</option>
                            <option value="1" {{ old('metode_bayar', $row->metode_bayar) == '1' ? 'selected' : '' }}>Bank</option>
                            <option value="2" {{ old('metode_bayar', $row->metode_bayar) == '2' ? 'selected' : '' }}>E-Wallet</option>
                          </select>
                        <label for="metode_bayar">Metode Bayar</label>
                        <div class="invalid-feedback">Metode Bayar wajib diisi.</div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <div class="btn-group w-100 custom-btn-group" role="group">
                          <button type="button" class="btn btn-secondary rounded-start px-4" onclick="backToStep1()">Back</button>
                          <button type="submit" class="btn btn-primary rounded-end px-4">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<script>
    function goToStep2() {
        const step1 = document.getElementById("step1");
        const inputs = step1.querySelectorAll("input, select");
        let isValid = true;

        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add("is-invalid");
                isValid = false;
            } else {
                input.classList.remove("is-invalid");
            }
        });

        if (!isValid) return;

        // Salin nilai ke hidden input
        document.getElementById('meja_id_hidden').value = document.getElementById('meja_id').value;
        document.getElementById('jam_mulai_hidden').value = document.getElementById('jam_mulai').value;
        document.getElementById('durasi_hidden').value = document.getElementById('durasi').value;

        // Nonaktifkan input asli agar user tidak ubah lagi (optional readonly atau disabled)
        document.getElementById('meja_id').disabled = true;
        document.getElementById('jam_mulai').readOnly = true;
        document.getElementById('durasi').readOnly = true;

        // Lanjut ke Step 2
        document.getElementById("step2").style.display = "block";
        step1.querySelector("button").disabled = true;
    }
  
    function backToStep1() {
        const step1 = document.getElementById("step1");
        const inputs = step1.querySelectorAll("input, select, button");

        inputs.forEach(el => {
            if (el.tagName === 'SELECT' && el.hasAttribute('data-readonly')) {
            el.disabled = false;
            } else if (el.tagName !== 'BUTTON') {
            el.readOnly = false;
            }
        });

        document.getElementById("step2").style.display = "none";
        step1.querySelector("button").disabled = false;
    }
</script> 
<script>
    function calculateTotal() {
        const mejaSelect = document.getElementById('meja_id');
        const durasiInput = document.getElementById('durasi');
        const totalDisplay = document.getElementById('total');
        const totalHidden = document.getElementById('total_bayar');

        const selectedOption = mejaSelect.options[mejaSelect.selectedIndex];
        const harga = parseInt(selectedOption.getAttribute('data-harga') || 0);
        const durasi = parseInt(durasiInput.value || 0);

        if (harga > 0 && durasi > 0) {
            const total = harga * durasi;
            totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
            totalHidden.value = total;
        } else {
            totalDisplay.value = '';
            totalHidden.value = '';
        }
    }

    // Event listener
    document.getElementById('meja_id').addEventListener('change', calculateTotal);
    document.getElementById('durasi').addEventListener('input', calculateTotal);
</script>   
  
@endsection
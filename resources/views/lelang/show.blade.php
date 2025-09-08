@extends('layouts.app')

@section('content')
<div class="card" style="max-width:500px;margin:auto;padding:20px">
    <h2 style="margin-bottom:16px">Checkout Barang</h2>

    <div style="margin-bottom:20px">
        <strong>{{ $barang->nama_barang }}</strong>
        <p class="muted">{{ $barang->deskripsi }}</p>
        <p><strong>Harga: </strong>Rp {{ number_format($barang->harga_akhir ?? $barang->harga_awal, 0, ',', '.') }}</p>
    </div>

    <form id="checkoutForm" action="{{route('payment')}}" method="POST">
        @csrf
        <input type="hidden" id="id" name="id" value="{{ $barang->id }}">

        <div style="margin-bottom:12px">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required placeholder="Masukkan nama">
        </div>

        <div style="margin-bottom:12px">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Masukkan email">
        </div>

        <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
        <div id="status" class="muted" style="margin-top:10px"></div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const form = document.getElementById('checkoutForm');
    const statusEl = document.getElementById('status');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        statusEl.textContent = 'Membuat invoice…';

        const formData = new FormData(form);

        try {
            const res = await fetch("{{ route('payment') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            if (!res.ok) {
                throw new Error(await res.text());
            }

            const data = await res.json();
            if (!data.invoice_url) {
                throw new Error(data.message || 'Invoice URL tidak ditemukan');
            }

            window.location.href = data.invoice_url;
        } catch (err) {
            statusEl.textContent = err.message || 'Terjadi kesalahan';
        }
    });
</script>
@endpush

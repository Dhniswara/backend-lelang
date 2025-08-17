@extends('layouts.app')

@section('content')
<div class="card">
  <h2 style="margin-top:0">Transaksi Saya</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Barang</th>
        <th>Harga</th>
        <th>Status</th>
        <th class="right">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($transactions as $trx)
      <tr>
        <td>#{{ $trx->id }}</td>
        <td>{{ $trx->barang->nama_barang ?? '-' }}</td>
        <td>Rp {{ number_format($trx->price, 0, ',', '.') }}</td>
        <td>{{ strtoupper($trx->status) }}</td>
        <td class="right">
          @if(in_array(strtolower($trx->status), ['pending','unpaid']) && $trx->checkout_link)
            <a class="btn-link" href="{{ $trx->checkout_link }}">Lanjutkan Bayar →</a>
          @else
            <span class="muted">-</span>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="muted">Belum ada transaksi.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
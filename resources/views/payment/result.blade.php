@extends('layouts.app')

@section('content')
@php
  // Opsi A (disarankan): backend set `session('payment_result')` berisi array {status, external_id}
  $result = session('payment_result') ?? null;
  // Opsi B: jika Anda mengandalkan query string, misal /payment/result?external_id=xxx&status=PAID
  $qs = [ 'external_id' => request('external_id'), 'status' => request('status') ];
@endphp

<div class="card">
  <h2 style="margin-top:0">Status Pembayaran</h2>

  @if($result)
    <p class="muted">External ID: <strong>{{ $result['external_id'] }}</strong></p>
    <h3>{{ strtoupper($result['status']) }}</h3>
  @else
    <p class="muted">External ID: <strong>{{ $qs['external_id'] ?? '-' }}</strong></p>
    <h3>{{ $qs['status'] ? strtoupper($qs['status']) : 'Tidak diketahui' }}</h3>
  @endif

  <div style="margin-top:16px">
    <a class="btn" href="{{ route('transactions.index') }}">Lihat Transaksi</a>
  </div>
</div>
@endsection
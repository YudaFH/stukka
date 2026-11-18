@extends('errors.layout')

@section('title', '419 - Sesi Telah Berakhir')
@section('code', '419')
@section('icon', 'clock-alert')
@section('badge_bg', 'bg-amber-50')
@section('badge_border', 'border-amber-200')
@section('icon_color', 'text-amber-600')

@section('headline', 'Sesi Halaman Kadaluarsa')

@section('message')
    Halaman ini dibiarkan terbuka terlalu lama tanpa aktivitas sehingga sesi keamanan form telah berakhir.
@endsection

@section('tip')
    Silakan muat ulang (refresh) halaman atau klik tombol Masuk kembali untuk melanjutkan aktivitas Anda secara aman.
@endsection

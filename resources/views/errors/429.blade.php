@extends('errors.layout')

@section('title', '429 - Terlalu Banyak Permintaan')
@section('code', '429')
@section('icon', 'activity')
@section('badge_bg', 'bg-purple-50')
@section('badge_border', 'border-purple-200')
@section('icon_color', 'text-purple-600')

@section('headline', 'Terlalu Banyak Permintaan')

@section('message')
    Sistem mendeteksi terlalu banyak permintaan dalam waktu singkat dari koneksi atau perangkat Anda.
@endsection

@section('tip')
    Harap tunggu sekitar 1 menit sebelum mencoba memuat kembali halaman ini.
@endsection

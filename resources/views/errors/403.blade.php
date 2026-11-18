@extends('errors.layout')

@section('title', '403 - Akses Dibatasi')
@section('code', '403')
@section('icon', 'shield-alert')
@section('badge_bg', 'bg-red-50')
@section('badge_border', 'border-red-200')
@section('icon_color', 'text-red-600')

@section('headline', 'Akses Halaman Dibatasi')

@section('message')
    Akun Anda tidak memiliki izin atau hak akses yang diperlukan untuk membuka halaman atau direktori ini.
@endsection

@section('tip')
    Jika Anda merasa ini sebuah kekeliruan, silakan hubungi Administrator sistem atau login menggunakan akun dengan hak akses yang sesuai.
@endsection

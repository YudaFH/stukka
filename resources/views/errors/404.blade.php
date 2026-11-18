@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')
@section('code', '404')
@section('icon', 'compass')
@section('badge_bg', 'bg-orange-50')
@section('badge_border', 'border-orange-200')
@section('icon_color', 'text-[#F7941D]')

@section('headline', 'Halaman Tidak Ditemukan')

@section('message')
    Maaf, tautan atau halaman yang Anda cari mungkin telah dipindahkan, berganti alamat URL, atau sudah tidak tersedia.
@endsection

@section('tip')
    Pastikan ejaan alamat web yang Anda ketik sudah benar, atau gunakan tombol di bawah untuk kembali ke halaman utama Stukka.
@endsection

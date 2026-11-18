@extends('errors.layout')

@section('title', '503 - Pemeliharaan Sistem')
@section('code', '503')
@section('icon', 'wrench')
@section('badge_bg', 'bg-blue-50')
@section('badge_border', 'border-blue-200')
@section('icon_color', 'text-[#001D5E]')

@section('headline', 'Sistem Sedang Dalam Pemeliharaan')

@section('message')
    Kami sedang melakukan peningkatan performa dan pemeliharaan berkala untuk memberikan layanan yang lebih optimal.
@endsection

@section('tip')
    Layanan akan segera online kembali dalam beberapa saat. Terima kasih atas pengertian dan kesabaran Anda.
@endsection

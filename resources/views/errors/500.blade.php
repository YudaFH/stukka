@extends('errors.layout')

@section('title', '500 - Terjadi Kendala Server')
@section('code', '500')
@section('icon', 'server-crash')
@section('badge_bg', 'bg-red-50')
@section('badge_border', 'border-red-200')
@section('icon_color', 'text-red-600')

@section('headline', 'Terjadi Kendala di Server')

@section('message')
    Maaf, server kami sedang mengalami gangguan internal sementara saat memproses permintaan Anda.
@endsection

@section('tip')
    Tim teknis Stukka telah diberi tahu mengenai kendala ini. Silakan coba kembali beberapa saat lagi.
@endsection

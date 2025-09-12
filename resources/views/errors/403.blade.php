@extends('layouts.app')

@section('title', '403 Forbidden')

@section('content')
    <div class="text-center">
        <h1>🚫 403</h1>
        <p>ليس لديك صلاحية للوصول إلى هذه الصفحة</p>
        <a href="{{ url('/') }}" class="btn btn-primary">العودة للرئيسية</a>
    </div>
@endsection

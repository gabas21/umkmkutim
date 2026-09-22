@extends('layouts.admin')

@section('title', 'Edit Event — Panel Admin')

@section('admin-content')
<div class="p-6 sm:p-8">
    <div class="mb-6">
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke daftar event
        </a>
        <h1 class="text-xl font-black text-slate-900">Edit Event</h1>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.events._form')
        </form>
    </div>
</div>
@endsection

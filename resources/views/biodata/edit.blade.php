@extends('layouts.app')

@section('title', 'Edit Biodata')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-bold">Edit Biodata</h1>

    <form method="POST" action="{{ route('biodata.update') }}" class="space-y-4 rounded-xl bg-white p-6 card-shadow">
        @csrf
        @method('PUT')
        @include('biodata.partials.form-fields')
        <button type="submit" class="btn-primary">Update</button>
    </form>
</div>
@endsection

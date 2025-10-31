@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Track new card</h3>

    <form action="{{ route('track.store') }}" method="post">
        @csrf
        <div class="mb-3">
            <label>Source URL</label>
            <input type="url" name="source_url" class="form-control" required placeholder="https://yuyu-tei.jp/..." value="{{ old('source_url') }}">
        </div>
        <div class="mb-3">
            <label>Optional card name</label>
            <input type="text" name="card_name" class="form-control" value="{{ old('card_name') }}">
        </div>
        <button class="btn btn-primary">Start tracking</button>
    </form>
</div>
@endsection

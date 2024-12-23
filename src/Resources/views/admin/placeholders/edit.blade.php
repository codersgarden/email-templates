@extends('email-templates::layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('email-templates::messages.edit_placeholder') }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.placeholders.update', $placeholder->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">{{ __('email-templates::messages.name') }}</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $placeholder->name) }}">
            </div>
            <div class="form-group">
                <label for="description">{{ __('email-templates::messages.description') }}</label>
                <input type="text" name="description" id="description" class="form-control"
                    value="{{ old('description', $placeholder->description) }}">
            </div>
            <button type="submit" class="btn btn-primary">{{ __('email-templates::messages.update') }}</button>
            <a href="{{ route('admin.placeholders.index') }}"
                class="btn btn-secondary">{{ __('email-templates::messages.cancel') }}</a>
        </form>
    </div>
@endsection

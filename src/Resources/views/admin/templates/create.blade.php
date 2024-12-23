<!-- src/Resources/views/admin/templates/create.blade.php -->

@extends('email-templates::layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('email-templates::messages.create_template') }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('email-templates.admin.templates.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="identifier">{{ __('email-templates::messages.identifier') }}</label>
                <input type="text" name="identifier" id="identifier" class="form-control" required>
                <small class="form-text text-muted">{{ __('email-templates::messages.identifier_help') }}</small>
            </div>

            <div class="form-group">
                <label for="placeholders">{{ __('email-templates::messages.select_placeholders') }}</label>
                <select name="placeholders[]" id="placeholders" class="form-control" multiple>
                    @foreach ($availablePlaceholders as $placeholder)
                        <option value="{{ $placeholder->id }}">{{ $placeholder->name }} - {{ $placeholder->description }}
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">{{ __('email-templates::messages.select_placeholders_help') }}</small>
            </div>

            @foreach ($locales as $locale)
                <div class="card mb-3">
                    <div class="card-header">
                        {{ strtoupper($locale) }} {{ __('email-templates::messages.translation') }}
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label
                                for="translations[{{ $locale }}][subject]">{{ __('email-templates::messages.subject') }}</label>
                            <input type="text" name="translations[{{ $locale }}][subject]"
                                id="translations[{{ $locale }}][subject]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label
                                for="translations[{{ $locale }}][body]">{{ __('email-templates::messages.body') }}</label>
                            <textarea name="translations[{{ $locale }}][body]" id="translations[{{ $locale }}][body]"
                                class="form-control rich-text-editor" rows="5" required></textarea>
                            <small class="form-text text-muted">
                                {{ __('email-templates::messages.available_placeholders') }}:
                                @foreach ($availablePlaceholders as $placeholder)
                                    <code>{{ '{{' }}{{ $placeholder->name }}{{ ' ?>' }}' }}</code>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-success">{{ __('email-templates::messages.save') }}</button>
            <a href="{{ route('email-templates.admin.templates.index') }}"
                class="btn btn-secondary">{{ __('email-templates::messages.cancel') }}</a>
        </form>
    </div>
@endsection

@section('scripts')
    <!-- Include CKEditor or any other rich text editor if desired -->
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <script>
        document.querySelectorAll('.rich-text-editor').forEach((textarea) => {
            CKEDITOR.replace(textarea.id);
        });
    </script>
@endsection

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

        <form action="{{ route('admin.templates.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="identifier">{{ __('email-templates::messages.identifier') }}</label>
                <input type="text" name="identifier" id="identifier" class="form-control">
                <small class="form-text text-muted">{{ __('email-templates::messages.identifier_help') }}</small>
            </div>

            <div class="form-group">
                <label for="placeholders">{{ __('email-templates::messages.select_placeholders') }}</label>
                <select class="form-select" name="placeholders[]" id="placeholders" aria-label="Default select example"
                    multiple="multiple">
                    @foreach ($availablePlaceholders as $placeholder)
                        <option value="{{ $placeholder->id }}">
                            {{ $placeholder->name }}
                        </option>
                    @endforeach
                </select>

                <small class="form-text text-muted">{{ __('email-templates::messages.select_placeholders_help') }}</small>
            </div>

            @foreach ($locales as $locale)
                <div class="card mb-3">
                    <div class="card-header">
                        <input type="hidden" name="translation[{{ $locale }}]" value="{{ $locale }}">
                        {{ strtoupper($locale) }} {{ __('email-templates::messages.translation') }}
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label
                                for="translations[{{ $locale }}][subject]">{{ __('email-templates::messages.subject') }}</label>
                            <input type="text" name="translations[{{ $locale }}][subject]"
                                id="translations[{{ $locale }}][subject]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label
                                for="translations[{{ $locale }}][body]">{{ __('email-templates::messages.body') }}</label>
                            {{-- <textarea name="translations[{{ $locale }}][body]" id="translations[{{ $locale }}][body]"
                                class="form-control rich-text-editor" rows="5"></textarea> --}}
                            <textarea name="translations[{{ $locale }}][body]" id="translations[{{ $locale }}][body]" hidden></textarea>
                            <div id="content" rows="5">{!! old('translations[{{ $locale }}][body]') !!}</div>

                        </div>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-success">{{ __('email-templates::messages.save') }}</button>
            <a href="{{ route('admin.templates.index') }}"
                class="btn btn-secondary">{{ __('email-templates::messages.cancel') }}</a>
        </form>
    </div>
@endsection

@section('scripts')
   
    <script>
        document.querySelectorAll('textarea').forEach((textarea) => {
            if (textarea.id.startsWith('translations')) {
                const quillContainer = document.createElement('div');
                quillContainer.style.height = '200px'; // Set height for consistency
                textarea.insertAdjacentElement('beforebegin', quillContainer);
                textarea.style.display = 'none'; // Hide the textarea

                const quill = new Quill(quillContainer, {
                    theme: 'snow',
                });

                // Sync Quill content back to the hidden textarea
                quill.on('text-change', function() {
                    textarea.value = quill.root.innerHTML;
                });
            }
        });
    </script>
@endsection

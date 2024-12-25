@extends('email-templates::layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('email-templates::messages.edit_template') }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.templates.update', $template->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Correct HTTP method -->
            <div class="form-group">
                <label for="identifier">{{ __('email-templates::messages.identifier') }}</label>
                <input type="text" name="identifier" id="identifier" class="form-control"
                    value="{{ old('identifier', $template->identifier) }}">
                <small class="form-text text-muted">{{ __('email-templates::messages.identifier_help') }}</small>
            </div>

            <div class="form-group">
                <label for="placeholders">{{ __('email-templates::messages.select_placeholders') }}</label>
                <select class="form-select" name="placeholders[]" id="placeholders" aria-label="Default select example"
                    multiple="multiple">
                    @foreach ($availablePlaceholders as $placeholder)
                        <option value="{{ $placeholder->id }}"
                            {{ in_array($placeholder->id, $selectedPlaceholders) ? 'selected' : '' }}>
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
                                id="translations[{{ $locale }}][subject]" class="form-control"
                                value="{{ old('translations.' . $locale . '.subject', $translations[$locale]['subject'] ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label
                                for="translations[{{ $locale }}][body]">{{ __('email-templates::messages.body') }}</label>
                            <textarea name="translations[{{ $locale }}][body]" id="translations[{{ $locale }}][body]" hidden>
                                {!! old('translations.' . $locale . '.body', $translations[$locale]['body'] ?? '') !!}
                            </textarea>
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

                // Set initial content for Quill
                quill.root.innerHTML = textarea.value.trim(); // Trimmed for safety

                // Sync Quill content back to the hidden textarea
                quill.on('text-change', function() {
                    textarea.value = quill.root.innerHTML;
                });
            }
        });
    </script>
@endsection

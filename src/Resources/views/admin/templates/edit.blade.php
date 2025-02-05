@extends('email-templates::layouts.admin')

@section('content')
    <div class="content">
        <div class="ms-5 me-5 mt-4">
            <!-- Breadcrumb -->
            <nav class="mb-3">
                <a href="{{ route('admin.templates.index') }}" class="fw-400 text-decoration-none">Email Template</a>
                <img src="{{ url('pervious-icon') }}" alt="Logo" class="fw-400 mx-2">
                <span class="fw-400">Edit Template</span>
            </nav>

            <!-- Title -->
            <h2 class="title pt-2 text-uppercase">Edit Template</h2>
        </div>

        <!-- Centered Form -->
        <div class="container mt-5">
            <form action="{{ route('admin.templates.update', $template->id) }}" method="POST"
                class="mx-auto rounded col-md-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" role="tablist">
                        <!-- General Tab -->
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                        </li>

                        <!-- Locale Tabs -->
                        @foreach ($locales as $locale)
                            <li class="nav-item">
                                <button class="nav-link @if ($loop->first)  @endif "
                                    id="tab-{{ $locale }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#tab-{{ $locale }}" type="button" role="tab"
                                    aria-controls="tab-{{ $locale }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ strtoupper($locale) }}
                                    @if ($errors->has("translations.$locale.subject") || $errors->has("translations.$locale.body"))
                                        <span class="text-danger">*</span>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3">
                        <!-- General Tab Content -->
                        <div id="general" class="container tab-pane active">
                            <div class="form-group">
                                <label for="identifier"
                                    class="form-label">{{ __('email-templates::messages.identifier') }}</label>
                                <input type="text" name="identifier" id="identifier" class="form-control"
                                    value="{{ old('identifier', $template->identifier) }}">
                                @error('identifier')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small
                                    class="form-text text-muted">{{ __('email-templates::messages.identifier_help') }}</small>
                            </div>

                            <div class="form-group">
                                <label for="placeholders"
                                    class="form-label mt-2">{{ __('email-templates::messages.select_placeholders') }}</label>
                                <select class="form-select" name="placeholders[]" id="placeholders" multiple>
                                    @foreach ($availablePlaceholders as $placeholder)
                                        <option value="{{ $placeholder->id }}"
                                            @if (in_array($placeholder->id, old('placeholders', $selectedPlaceholders))) selected @endif>
                                            {{ $placeholder->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('placeholders')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <small
                                    class="form-text text-muted">{{ __('email-templates::messages.select_placeholders_help') }}</small>
                            </div>
                           

                            <!-- File Section -->
                            <div class="form-group">
                                <label for="attachment" class="form-label mt-2">Attachment</label>
                                <div class="form-check">
                                    <input type="checkbox" name="attachment" id="attachment" class="form-check-input" 
                                        @if(old('attachment', $template->has_attachment) == 1) checked @endif>
                                    <label class="form-check-label" for="attachment">Include Attachment</label>
                                </div>
                                @error('attachment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Locale Tabs Content -->
                        @foreach ($locales as $locale)
                            <div class="tab-pane fade @if ($loop->first) show @endif"
                                id="tab-{{ $locale }}" role="tabpanel"
                                aria-labelledby="tab-{{ $locale }}-tab">

                                <div class="form-group">
                                    <label for="translations[{{ $locale }}][subject]"
                                        class="form-label ">{{ __('email-templates::messages.subject') }}</label>
                                    <input type="text" class="form-control"
                                        name="translations[{{ $locale }}][subject]"
                                        id="translations[{{ $locale }}][subject]" class="form-control"
                                        value="{{ old('translations.' . $locale . '.subject', $translations[$locale]['subject'] ?? '') }}">

                                    @error("translations.$locale.subject")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="translations[{{ $locale }}][body]" class="form-label mt-2">
                                        {{ __('email-templates::messages.body') }}
                                    </label>
                                    <textarea name="translations[{{ $locale }}][body]" id="translations[{{ $locale }}][body]" hidden
                                        class="custom-textarea">{{ old('translations.' . $locale . '.body', $translations[$locale]['body'] ?? '') }}</textarea>
                                    <div id="content-{{ $locale }}" class="editor custom-editor">
                                        {!! old('translations.' . $locale . '.body', $translations[$locale]['body'] ?? '') !!}</div>
                                    @error("translations.$locale.body")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                    class="btn btn-dark fs-5">{{ __('email-templates::messages.placeholder.save') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.editor').forEach((editor, index) => {
            const locale = editor.id.split('-')[1]; // Get locale from editor ID
            const textarea = document.getElementById(`translations[${locale}][body]`);
            const oldContent = textarea.value || ''; // Get the old content from textarea

            // Initialize Quill editor with the old content
            const quill = new Quill(`#content-${locale}`, {
                theme: 'snow',
            });

            // Set the content of the editor to the old value
            quill.root.innerHTML = oldContent;

            // Sync content of editor with the hidden textarea
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
        });


       
    </script>
@endsection

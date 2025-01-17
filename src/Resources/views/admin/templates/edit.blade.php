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
                                <label for="file" class="form-label mt-2">Attachment</label>
                                <input type="file" name="files[]" id="files" class="form-control" multiple>


                                @if ($template->file)
                                    <div class="mt-2" id="file-list">
                                        @php
                                            // Split the comma-separated string into an array
                                            $files = explode(',', $template->file);
                                        @endphp

                                        @if (count($files) > 0)
                                            @foreach ($files as $file)
                                                @php
                                                    $filePath = asset('storage/images/' . $file);
                                                    $fileName = pathinfo($file, PATHINFO_FILENAME);
                                                    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                                @endphp

                                                <div class="mt-2 d-flex align-items-center justify-content-between"
                                                    id="file-{{ $loop->index }}">
                                                    <div class="d-flex align-items-center">
                                                        @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                                            <div class="mt-2">
                                                                <img src="{{ $filePath }}" alt="File Preview"
                                                                    class="img-fluid" width="150px" height="150px" />
                                                            </div>
                                                        @elseif (strtolower($fileExtension) === 'pdf')
                                                            <div class="mt-2">
                                                                {{ $fileName }}.{{ $fileExtension }}
                                                                <a href="{{ $filePath }}" target="_blank"
                                                                    class="">View PDF</a>
                                                            </div>
                                                        @else
                                                            <div class="mt-2">
                                                                <a href="{{ $filePath }}" target="_blank"
                                                                    class="">View File</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <!-- Delete Icon -->
                                                    <button type="button"
                                                        class="btn btn-link text-danger p-0 btn-delete-file"
                                                        data-file="{{ $file }}"
                                                        data-template-id="{{ $template->id }}"
                                                        data-index="{{ $loop->index }}">
                                                        <i class="bi bi-trash fs-4"></i>
                                                    </button>

                                                </div>
                                            @endforeach
                                        @else
                                            <p>No valid files available.</p>
                                        @endif
                                    </div>
                                @endif

                                @error('file')
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


        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete-file');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fileName = this.getAttribute('data-file');
                    const templateId = this.getAttribute('data-template-id');
                    const fileIndex = this.getAttribute('data-index');
                    const fileElement = document.getElementById(`file-${fileIndex}`);

                    if (confirm('Are you sure you want to delete this file?')) {
                        fetch('{{ route('admin.templates.delete-file') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    file_name: fileName,
                                    template_id: templateId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    // Remove the file from the DOM
                                    fileElement.remove();
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while deleting the file.');
                            });
                    }
                });
            });
        });
    </script>
@endsection

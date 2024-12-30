@extends('email-templates::layouts.admin')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between m-4 align-items-center ms-lg-5 me-lg-5 ms-md-0 me-md-0 ms-sm-0 me-sm-0">
            <div class="d-flex justify-content-start">
                <h2 class="text-uppercase fw-semi-bold">{{ __('email-templates::messages.placeholders') }}</h2>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-dark mb-3 create-placeholder-btn" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                    {{ __('email-templates::messages.create_placeholder') }}
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const successAlert = document.getElementById('success-alert');
                    if (successAlert) {
                        setTimeout(() => {
                            successAlert.style.transition = "opacity 0.5s ease";
                            successAlert.style.opacity = 0;
                            setTimeout(() => {
                                successAlert.style.display = 'none';
                            }, 500);
                        }, 3000);
                    }
                });
            </script>
        @endif

        <table class="table table-light">
            <thead class="thead-dark">
                <tr class="text-start">
                    <th class="ps-4">{{ __('email-templates::messages.name') }}</th>
                    <th>{{ __('email-templates::messages.description') }}</th>
                    <th>{{ __('email-templates::messages.placeholder.data_type') }}</th>
                    <th width="15%" class="text-center">{{ __('email-templates::messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($placeholders as $placeholder)
                    <tr>
                        <td class="ps-4 align-middle">{{ $placeholder->name }}</td>
                        <td class="align-middle">{{ $placeholder->description ?? '-' }}</td>
                        <td class="align-middle">{{ $placeholder->data_type ?? '-' }}</td>
                        <td class="text-center">
                            <button class="btn btn-ghost mb-3 edit-placeholder-btn" type="button"
                                data-id="{{ $placeholder->id }}" data-name="{{ $placeholder->name }}"
                                data-description="{{ $placeholder->description }}"
                                data-data_type="{{ $placeholder->data_type }}" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            {{ __('email-templates::messages.no_placeholders_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>


        @include('email-templates::layouts.pagination', ['paginator' => $placeholders])
    </div>

    <div class="offcanvas offcanvas-end {{ $errors->any() ? 'show' : '' }}" tabindex="-1" id="offcanvasRight"
        data-bs-backdrop="static" aria-labelledby="offcanvasRightLabel"
        style="{{ $errors->any() ? 'visibility: visible;' : '' }}">
        <div class="container p-5">
            <div class="offcanvas-header d-flex justify-content-between align-items-center">
                <h2 class="text-uppercase fw-semi-bold" id="offcanvas-title">
                    {{ old('_method') === 'PUT' ? __('email-templates::messages.edit_placeholder') : __('email-templates::messages.create_placeholder') }}
                </h2>
                <a href="{{ route('admin.placeholders.index') }}">
                    <img class="btn btn-ghost text-reset d-flex justify-content-end" data-bs-dismiss="offcanvas"
                        aria-label="Close" src="{{ url('close-icon') }}" alt="">
                </a>
            </div>
            <div class="offcanvas-body">
                <form id="placeholder-form" method="POST"
                    action="{{ old('_method') === 'PUT' ? route('admin.placeholders.update', old('id')) : route('admin.placeholders.store') }}">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="{{ old('_method', 'POST') }}">
                    <input type="hidden" name="id" value="{{ old('id') }}">

                    <div class="form-group mb-3">
                        <label for="name" class="form-label">{{ __('email-templates::messages.name') }}<span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="{{ __('email-templates::messages.input_placeholder.name') }}"
                            value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">{{ __('email-templates::messages.description') }}<span
                                class="text-danger">*</span></label>
                        <textarea rows="6" name="description" id="description" class="form-control"
                            placeholder="{{ __('email-templates::messages.input_placeholder.description') }}">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="data_type"
                            class="form-label">{{ __('email-templates::messages.placeholder.data_type') }}<span
                                class="text-danger">*</span></label>
                        <select name="data_type" id="data_type" class="form-select">
                            <option value="" disabled>
                                {{ __('email-templates::messages.input_placeholder.data_type') }}</option>
                            @foreach (config('email-templates.placeholder_data_types') as $type)
                                <option value="{{ $type }}" {{ old('data_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('data_type'))
                            <span class="text-danger">{{ $errors->first('data_type') }}</span>
                        @endif
                    </div>

                    <div class="offcanvas-footer d-flex justify-content-start pt-3">
                        <button type="submit"
                            class="btn btn-dark">{{ __('email-templates::messages.placeholder.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('placeholder-form');
        const methodField = document.getElementById('form-method');
        const offcanvasTitle = document.getElementById('offcanvas-title');

        // Check if there are validation errors on load
        if (document.querySelector('.offcanvas.show')) {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasRight'));
            offcanvas.show();
        }

        // Handle 'Create' button click
        document.querySelector('.create-placeholder-btn').addEventListener('click', function() {
            resetForm();
            openOffcanvas("{{ __('email-templates::messages.create_placeholder') }}");
        });

        // Handle 'Edit' button click
        document.querySelectorAll('.edit-placeholder-btn').forEach(button => {
            button.addEventListener('click', function() {
                const {
                    id,
                    name,
                    description,
                    data_type
                } = this.dataset;
                populateForm(id, name, description, data_type);
                openOffcanvas("{{ __('email-templates::messages.edit_placeholder') }}");
            });
        });

        function resetForm() {
            form.action = "{{ route('admin.placeholders.store') }}";
            methodField.value = "POST";
            form.querySelector('input[name="name"]').value = '';
            form.querySelector('textarea[name="description"]').value = '';
            form.querySelector('select[name="data_type"]').value = '';
            clearErrors();
        }

        function populateForm(id, name, description, dataType) {
            form.action = `{{ route('admin.placeholders.update', ':id') }}`.replace(':id', id);
            methodField.value = "PUT";
            form.querySelector('input[name="name"]').value = name;
            form.querySelector('textarea[name="description"]').value = description;
            form.querySelector('select[name="data_type"]').value = dataType;
            form.querySelector('input[name="id"]').value = id; // Ensure the ID is set
            clearErrors();
        }

        function clearErrors() {
            document.querySelectorAll('.text-danger').forEach(error => error.textContent = '');
        }

        function openOffcanvas(title) {
            offcanvasTitle.textContent = title;
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasRight'));
            offcanvas.show();
        }
    });
</script>


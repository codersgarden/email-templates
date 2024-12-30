<!-- src/Resources/views/admin/placeholders/index.blade.php -->

@extends('email-templates::layouts.admin')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between m-4 mb-2">
            <div class="d-flex justify-content-start">
                <h2 class="text-uppercase fw-semi-bold">{{ __('email-templates::messages.placeholders') }}</h2>
            </div>
            <div class="d-flex justify-content-end">
                {{-- <a href="{{ route('admin.placeholders.create') }}" class="btn btn-dark mb-3">
                    {{ __('email-templates::messages.create_placeholder') }}
                </a> --}}

                <button class="btn btn-dark mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                    aria-controls="offcanvasRight">{{ __('email-templates::messages.create_placeholder') }}</button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-light">
            <thead class="thead-dark">
                <tr class="text-start">
                    <th class="ps-4">{{ __('email-templates::messages.name') }}</th>
                    <th>{{ __('email-templates::messages.description') }}</th>
                    <th>{{ __('email-templates::messages.placeholder.data_type') }}</th>
                    <th width="15%" class="text-center ">{{ __('email-templates::messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($placeholders as $placeholder)
                    <tr>
                        <td class="ps-4 align-middle">{{ $placeholder->name }}</td>
                        <td class="align-middle">{{ $placeholder->description ?? '-' }}</td>
                        <td class="align-middle">{{ $placeholder->data_type ?? '-' }}</td>
                        <td class="text-center">

                            <button class="btn btn-ghost mb-3" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                    class="fa-solid fa-pen-to-square"></i></button>

                            <a href="{{ route('admin.placeholders.edit', $placeholder->id) }}"
                                class="text-dark decoration-none btn btn-ghost">

                            </a>

                            {{-- <form action="{{ route('admin.placeholders.destroy', $placeholder->id) }}" method="POST"
                                class="d-inline-block"
                                onsubmit="return confirm('{{ __('email-templates::messages.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost text-dark">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form> --}}
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
    </div>

    <div class="offcanvas offcanvas-end {{ $errors->any() ? 'show' : '' }}" tabindex="-1" id="offcanvasRight"
        data-bs-backdrop="static" aria-labelledby="offcanvasRightLabel"
        style="{{ $errors->any() ? 'visibility: visible;' : '' }}">
        <div class="container p-5">
            <div class="offcanvas-header d-flex justify-content-between align-items-center">
                <h2 class="text-uppercase fw-semi-bold align-self-center">
                    {{ __('email-templates::messages.create_placeholder') }}</h2>

                <a href="{{ route('admin.placeholders.index') }}">
                    <img class="btn btn-ghost text-reset d-flex justify-content-end" data-bs-dismiss="offcanvas"
                        aria-label="Close" src="{{ url('close-icon') }}" alt="">
                </a>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('admin.placeholders.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">{{ __('email-templates::messages.name') }}<span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name') }}"
                            placeholder='{{ __('email-templates::messages.input_placeholder.name') }}'>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="description" class="form-label">{{ __('email-templates::messages.description') }}<span
                                class="text-danger">*</span></label>
                        <textarea rows="6" type="text" name="description" id="description" class="form-control"
                            placeholder='{{ __('email-templates::messages.input_placeholder.description') }}'>{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="type"
                            class="form-label">{{ __('email-templates::messages.placeholder.data_type') }}<span
                                class="text-danger">*</span></label>
                        <select name="data_type" id="type" class="form-select cursor-pointer">
                            <option value="" disabled {{ old('data_type') ? '' : 'selected' }}>
                                {{ __('email-templates::messages.input_placeholder.data_type') }}
                            </option>
                            @foreach (config('email-templates.placeholder_data_types') as $type)
                                <option value="{{ $type }}" {{ old('data_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('data_type'))
                            <span class="text-danger">{{ $errors->first('data_type') }}</span>
                        @endif
                    </div>
                    <div class="offcanvas-footer d-flex justify-content-start pt-3">
                        <button type="submit"
                            class="btn btn-dark">{{ __('email-templates::messages.placeholder.create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

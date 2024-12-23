<!-- src/Resources/views/admin/placeholders/index.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('email-templates::messages.placeholders') }}</h1>
        <a href="{{ route('email-templates.admin.placeholders.create') }}" class="btn btn-primary mb-3">
            {{ __('email-templates::messages.create_placeholder') }}
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>{{ __('email-templates::messages.name') }}</th>
                    <th>{{ __('email-templates::messages.description') }}</th>
                    <th>{{ __('email-templates::messages.data_type') }}</th>
                    <th>{{ __('email-templates::messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($placeholders as $placeholder)
                    <tr>
                        <td>{{ $placeholder->name }}</td>
                        <td>{{ $placeholder->description ?? '-' }}</td>
                        <td>{{ $placeholder->data_type ?? '-' }}</td>
                        <td>
                            <a href="{{ route('email-templates.admin.placeholders.edit', $placeholder->id) }}"
                                class="btn btn-sm btn-warning">
                                {{ __('email-templates::messages.edit') }}
                            </a>
                            <form action="{{ route('email-templates.admin.placeholders.destroy', $placeholder->id) }}"
                                method="POST" class="d-inline-block"
                                onsubmit="return confirm('{{ __('email-templates::messages.confirm_delete_placeholder') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    {{ __('email-templates::messages.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('email-templates::messages.no_placeholders_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

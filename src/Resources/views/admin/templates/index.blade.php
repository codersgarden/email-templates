<!-- src/Resources/views/admin/templates/index.blade.php -->

@extends('email-templates::layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('email-templates::messages.email_templates') }}</h1>
        <a href="{{ route('email-templates.admin.templates.create') }}" class="btn btn-primary mb-3">
            {{ __('email-templates::messages.create_template') }}
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>{{ __('email-templates::messages.identifier') }}</th>
                    <th>{{ __('email-templates::messages.locale') }}</th>
                    <th>{{ __('email-templates::messages.subject') }}</th>
                    <th>{{ __('email-templates::messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    @foreach ($template->translations as $translation)
                        <tr>
                            <td>{{ $template->identifier }}</td>
                            <td>{{ strtoupper($translation->locale) }}</td>
                            <td>{{ Str::limit($translation->subject, 50) }}</td>
                            <td>
                                <a href="{{ route('email-templates.admin.templates.edit', $template->id) }}"
                                    class="btn btn-sm btn-warning">
                                    {{ __('email-templates::messages.edit') }}
                                </a>
                                <form action="{{ route('email-templates.admin.templates.destroy', $template->id) }}"
                                    method="POST" class="d-inline-block"
                                    onsubmit="return confirm('{{ __('email-templates::messages.confirm_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        {{ __('email-templates::messages.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="4" class="text-center">{{ __('email-templates::messages.no_templates_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

<!-- src/Resources/views/admin/templates/index.blade.php -->

@extends('email-templates::layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>{{ __('email-templates::messages.email_templates') }}</h1>
        <a href="{{ route('admin.templates.create') }}" class="btn btn-primary mb-3">
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
                    <th>{{ __('email-templates::messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>{{ $template->identifier }}</td>
                        <td>
                            <a href="{{ route('admin.templates.edit', $template->id) }}" class="btn btn-sm btn-warning">
                                {{ __('email-templates::messages.edit') }}
                            </a>

                            <form action="{{ route('admin.templates.destroy', $template->id) }}" class="d-inline-block"
                                method="POST"
                                onsubmit="return confirm('{{ __('email-templates::messages.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-sm btn-danger">{{ __('email-templates::messages.delete') }}</button>
                            </form>

                        </td>
                    </tr>
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


@section('scripts')
    <script>
        document.querySelectorAll('form[onsubmit]').forEach((form) => {
            form.addEventListener('submit', (e) => {
                if (!confirm(form.getAttribute('onsubmit').replace('return ', '').slice(0, -1))) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

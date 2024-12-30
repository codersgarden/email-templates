<!-- src/Resources/views/admin/templates/index.blade.php -->

@extends('email-templates::layouts.admin')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between m-4 mb-2">
            <div class="d-flex justify-content-start">
                <h2 class="text-uppercase fw-semi-bold">{{ __('email-templates::messages.email_templates') }}</h2>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.templates.create') }}" class="btn btn-dark mb-3">
                    {{ __('email-templates::messages.create_template') }}
                </a>
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
                    <th class="ps-4">{{ __('email-templates::messages.identifier') }}</th>
                    <th width="15%" class="text-center ">{{ __('email-templates::messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td class="ps-4 align-middle">{{ $template->identifier }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.templates.edit', $template->id) }}"
                                class="text-dark decoration-none btn btn-ghost">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.templates.destroy', $template->id) }}" class="d-inline-block"
                                method="POST"
                                onsubmit="return confirm('{{ __('email-templates::messages.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost text-dark">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
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

    @include('email-templates::layouts.pagination', ['paginator' => $templates])
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

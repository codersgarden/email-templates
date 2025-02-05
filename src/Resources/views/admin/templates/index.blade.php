<!-- src/Resources/views/admin/templates/index.blade.php -->

@extends('email-templates::layouts.admin')

@section('content')
    <div class="content bg-color">
        <div class="d-flex justify-content-between align-items-center ms-lg-5 me-lg-5 ms-md-0 me-md-0 ms-sm-0 me-sm-0">
            <p class="title pt-3 text-uppercase">{{ __('email-templates::messages.email_templates') }}</p>

            <div class="d-flex align-items-center ms-auto">
                <a href="{{ route('admin.templates.create') }}"
                    class="br-11 new_template btn btn-dark ms-3">{{ __('email-templates::messages.create_template') }}</a>

            </div>
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

    <table class="table table-light table-responsive">
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


                        <form action="{{ route('admin.templates.destroy', $template->id) }}" method="post"
                            class="d-inline delete-role-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm" id="deleteButton" title="Delete Role"
                                data-delete-type="permission">
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

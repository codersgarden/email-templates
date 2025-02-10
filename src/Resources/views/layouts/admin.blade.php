<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link rel="stylesheet" href="{{ url('email-templates/css/style.css') }}">
    </link>
    <link href="{{ url('email-templates/fonts/Jost-VariableFont_wght.ttf') }}">
    </link>
    <title>@yield('title')</title>

</head>

<body class="bg-light">
    @include('email-templates::layouts.navbar')
    @yield('content')
    @yield('scripts')
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://kit.fontawesome.com/9abc1ff96d.js" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener for delete buttons
        const deleteButtons = document.querySelectorAll('#deleteButton');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const templateId = this.dataset.templateId;
                const form = this.closest('form');

                // SweetAlert confirmation for deletion
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This email template will be deleted permanently.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f88c0',
                    cancelButtonColor: '#c0513a',
                    background: '#f9f9f9',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show success message
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The email template has been deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Submit the form after 2 seconds
                        setTimeout(() => {
                            form.submit();
                        }, 2000);
                    }
                });
            });
        });
    });
</script>


 <!-- Include Choices.js from CDN -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
 <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

 <script>
     document.addEventListener('DOMContentLoaded', function () {
         const placeholderSelect = document.getElementById('placeholders');
         if (placeholderSelect) {
             new Choices(placeholderSelect, {
                 removeItemButton: true,  // Allows users to remove selected options
                 searchEnabled: true,  // Enables search functionality
                 placeholder: true,
                 placeholderValue: "Select Placeholders",  // Custom placeholder text
             });
         }
     });
 </script>


</html>

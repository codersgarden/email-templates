<x-mail::layout>
{{-- Custom Header --}}
<x-slot:header>
@include('email-templates::vendor.mail.custom_header', ['url' => $url ?? '#', 'logoUrl' => $logoUrl])
</x-slot:header>

{{-- Email Body --}}
{!! $bodyContent !!}

{{-- Custom Footer --}}
<x-slot:footer>
@include('email-templates::vendor.mail.custom_footer')
</x-slot:footer>
</x-mail::layout>
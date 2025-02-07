<x-mail::layout>
<x-slot:header>
    @include('email-templates::vendor.mail.custom_header', ['url' => $url ?? '#', 'logoUrl' => $logoUrl])
</x-slot:header>
{!! $bodyContent !!}
</x-mail::layout>

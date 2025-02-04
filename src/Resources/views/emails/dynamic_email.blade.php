<x-mail::layout>
    
<x-slot:header>
<div style="text-align: center; margin-bottom: 20px;margin-top: 20px;">
<img src="{{ url($logo) }}" alt="Logo" style="max-width: 150px;">
</div>
</x-slot:header>

   
{!! $bodyContent !!}

   
<x-mail::button :url="$url">
{{ $button_text }}
</x-mail::button>
 

</x-mail::layout>



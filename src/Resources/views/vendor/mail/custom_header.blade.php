<tr>
    <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
        @if(!empty($logoUrl))
    <img src="{{ $logoUrl }}" alt="Custom Logo" style="max-width: 150px;">
    {{-- @else
    {{ $slot }} --}}
    @endif
    </a>
    </td>
    </tr>
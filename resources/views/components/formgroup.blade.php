@php
    $classlabel = in_array($type, ['text', 'password']) ? 'bmd-label-floating' : 'bmd-label-static';
@endphp
<div class="{{ $class }}">
    <div class="form-group {{ in_array($type, ['select']) ? 'bmd-form-group select-wizard' : '' }}">
        <label class="{{ $classlabel }}">{{ $label }}</label>
        @if ($type == 'text' || $type == 'password')
            <input type="{{ $type }}" class="form-control" {{ $attributes }}> {{-- placeholder="{{ $label }}" --}}
        @elseif ($type == 'select')
            <select class="form-control selectpicker" {{ $attributes }} data-style="select-with-transition">
                {{ $slot }}
            </select>
        @else
            {{ $slot }}
        @endif
    </div>
</div>

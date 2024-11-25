<button {{ $attributes->merge(['class' => 'btn btn-sm']) }}>
    {!! $icon !!} {{ $label }} {{ $slot }}
</button>

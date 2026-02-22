@props([
    'field' => null,   // The form field name, e.g. 'email' — used with @error
    'message' => null, // Optional hardcoded message; overrides @error lookup
])

@if($message)
    <p {{ $attributes->merge(['class' => 'text-xs text-red-500 mt-1.5']) }}>
        {{ $message }}
    </p>
@elseif($field)
    @error($field)
        <p {{ $attributes->merge(['class' => 'text-xs text-red-500 mt-1.5']) }}>
            {{ $message }}
        </p>
    @enderror
@endif

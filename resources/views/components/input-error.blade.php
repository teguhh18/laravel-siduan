@php
    $errorMessages = $name ? $errors->get($name) : $messages;
@endphp

@foreach ((array) $errorMessages as $message)
    <small {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }}>
        {{ $message }}
    </small>
@endforeach

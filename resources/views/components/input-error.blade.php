@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <p {{ $attributes->merge(['class' => 'error-text-metronic']) }}>
            {{ $message }}
        </p>
    @endforeach
@endif

@props(['disabled' => false, 'isError' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => ($isError ? 'input-error-metronic ' : '') . 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>

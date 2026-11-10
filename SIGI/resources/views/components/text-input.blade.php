@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-slate-1000 dark:focus:border-slate-600 focus:ring-slate-1000 dark:focus:ring-slate-600 rounded-md shadow-sm']) }}>

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:bg-green-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors']) }}>
    {{ $slot }}
</button>

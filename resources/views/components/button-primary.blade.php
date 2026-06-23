<button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 transform transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-300']) }}>
    {{ $slot }}
</button>

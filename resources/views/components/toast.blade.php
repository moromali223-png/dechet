@props(['type' => 'info','title' => null])
<div x-data="{open: true}" x-show="open" x-transition class="fixed top-4 right-4 bg-white border rounded-md shadow p-3 w-80">
  <div class="flex items-start">
    <div class="flex-1">
      @if($title)
        <p class="text-sm font-medium text-gray-900">{{ $title }}</p>
      @endif
      <p class="text-sm text-gray-600">{{ $slot }}</p>
    </div>
    <button @click="open = false" class="ml-4 text-gray-400 hover:text-gray-600">✕</button>
  </div>
</div>

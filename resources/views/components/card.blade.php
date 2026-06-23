<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm p-4 transition hover:shadow-md']) }}>
    @if(isset($header))
        <div class="flex items-start justify-between">
            <div class="space-y-1">
                <h3 class="text-sm font-medium text-gray-700">{{ $header }}</h3>
                @if(isset($sub))
                    <p class="text-xs text-gray-500">{{ $sub }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="ml-4">{{ $actions }}</div>
            @endif
        </div>
        <hr class="my-3 border-t">
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="mt-3 text-sm text-gray-500">{{ $footer }}</div>
    @endif
</div>

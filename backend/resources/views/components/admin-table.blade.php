@props(['headers' => []])

<div class="overflow-hidden rounded bg-white shadow">
    <table {{ $attributes->merge(['class' => 'w-full text-sm']) }}>
        @if($headers)
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-4 py-3 text-left">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
    </table>
</div>

<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-5 h-5 text-blue-600"></i>
            Select Division
        </h2>
    </x-slot>

    <div class="py-10 max-w-xl mx-auto px-4">

        <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">

            <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                <i data-lucide="building-2" class="w-5 h-5 text-gray-700"></i>
                Choose a Division
            </h3>

            <form action="{{ route('admin.reports.choose-report-type') }}" method="POST">
                @csrf

                <select name="division_id"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4">
                    <option value="">-- Select Division --</option>
                    @foreach($divisions as $division)
                        @if(in_array($division->division_name, [
                            'Gledco Enterprise - Laoag',
                            'Gledco Enterprise - Batac',
                            'Gledco Enterprise - Solsona'
                        ]))
                            <option value="{{ $division->id }}">
                                {{ $division->division_name }}
                            </option>
                        @endif
                    @endforeach
                </select>

                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Continue →
                </button>
            </form>

        </div>
    </div>

    <script>document.addEventListener("DOMContentLoaded",()=>{ lucide.replace(); });</script>

</x-app-layout>

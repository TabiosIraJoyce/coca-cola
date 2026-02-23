<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            <span class="inline-flex items-center gap-2">
                <i data-lucide="files" class="w-5 h-5 text-blue-600"></i>
                Select Report Type
            </span>
        </h2>
    </x-slot>

    <div class="py-10 max-w-3xl mx-auto px-4">

        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">

            <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                <i data-lucide="building" class="w-5 h-5 text-gray-700"></i>
                Division Selected:
                <span class="text-blue-700">{{ $division->division_name }}</span>
            </h3>

            {{-- STEP 2 FORM --}}
            <form action="{{ route('admin.reports.add.report-type') }}" method="POST">
                @csrf

                {{-- Keep this EXACTLY as is --}}
                <input type="hidden" name="division_id" value="{{ $division_id }}">

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Report Type
                </label>

                <select name="report_type"
                        class="block w-full rounded-md border-gray-300 shadow-sm 
                               focus:ring-blue-500 focus:border-blue-500 mb-4"
                        required>
                    <option value="">-- Select Report Type --</option>
                    <option value="receipts">📄 Receipts Breakdown</option>
                    <option value="remittance">💵 Remittance Details</option>
                    <option value="receivables">📊 Receivables Monitoring</option>
                    <option value="borrowers">📘 Borrower Agreement</option>
                </select>

                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Continue →
                </button>

            </form>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.replace();
        });
    </script>

</x-app-layout>

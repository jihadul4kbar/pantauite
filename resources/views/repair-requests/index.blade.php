@extends('layouts.app')

@section('title', 'Verifikasi Permintaan Perbaikan')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                Verifikasi Permintaan Perbaikan
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola dan verifikasi permintaan perbaikan dari pengguna sebelum dikonversi menjadi tiket
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form action="{{ route('repair-requests.admin.index') }}" method="GET" class="flex flex-wrap gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama, email, subjek, atau nomor..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <select name="priority" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Prioritas</option>
                        <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Submit & Reset Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('repair-requests.admin.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Requests Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor & Tanggal
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pemohon
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subjek
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prioritas
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($repairRequests as $request)
                            <tr class="hover:bg-gray-50">
                                <!-- Nomor & Tanggal -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-blue-600">
                                        {{ $request->request_number }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $request->created_at->format('d M Y') }}
                                    </div>
                                </td>

                                <!-- Pemohon -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">
                                        {{ $request->requester_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $request->requester_email }}
                                    </div>
                                    @if($request->requester_department)
                                        <div class="text-xs text-gray-500">
                                            {{ $request->requester_department }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Subjek -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $request->subject }}
                                    </div>
                                    @if($request->category)
                                        <div class="text-xs text-gray-500">
                                            {{ $request->category->name }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Prioritas -->
                                <td class="px-4 py-3">
                                    @if($request->priority === 'critical')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Critical
                                        </span>
                                    @elseif($request->priority === 'high')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            High
                                        </span>
                                    @elseif($request->priority === 'medium')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Medium
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Low
                                        </span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @if($request->status === 'draft')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Draft
                                        </span>
                                    @elseif($request->status === 'submitted')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Submitted
                                        </span>
                                    @elseif($request->status === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($request->status === 'rejected')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Converted
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-sm font-medium">
                                    <a
                                        href="{{ route('repair-requests.admin.show', $request->id) }}"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada permintaan perbaikan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($repairRequests->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $repairRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

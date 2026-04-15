@extends('layouts.auth')

@section('title', 'Form Permintaan Perbaikan')

@section('content')
<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <!-- Background Decorations -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-40 -right-40 w-80 h-80 sm:w-96 sm:h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 sm:w-96 sm:h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 w-64 h-64 sm:w-72 sm:h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <!-- Mobile-Optimized Navbar -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-white/20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 sm:space-x-3 group">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <span class="text-lg sm:text-xl font-bold text-gray-900">PantauITE</span>
                </a>
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-1.5 sm:space-x-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="hidden sm:inline">Beranda</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Hero Header - Mobile Optimized -->
            <div class="text-center mb-6 sm:mb-12">
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 sm:px-4 sm:py-2 bg-green-100 text-green-700 rounded-full text-xs sm:text-sm font-medium mb-4 sm:mb-6">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Tanpa Login Diperlukan</span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-3 sm:mb-4 leading-tight px-2">
                    Form Permintaan
                    <span class="block mt-1 bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Perbaikan TI</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed px-4">
                    Laporkan masalah pada perangkat atau sistem TI Anda. Tim IT Manager akan meninjau dan mengkonversi menjadi tiket layanan.
                </p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 sm:mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl sm:rounded-2xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-green-800">Berhasil!</h3>
                            <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 sm:mb-8 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl sm:rounded-2xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-7-9a7 7 0 1114 0 7 7 0 01-14 0zm3.707-3.707a1 1 0 00-1.414 1.414L7.586 9 5.293 6.707a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800">Terjadi Kesalahan</h3>
                            <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Container - Mobile Optimized -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden border border-white/20">
                <!-- Form Header Gradient -->
                <div class="h-1.5 sm:h-2 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500"></div>

                <div class="p-4 sm:p-8 md:p-12">
                    <form action="{{ route('repair-requests.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- Progress Steps - Mobile Vertical, Desktop Horizontal -->
                        <div class="mb-8 sm:mb-12">
                            <!-- Mobile: Vertical Steps -->
                            <div class="sm:hidden space-y-3">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                        1
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">Data Pemohon</p>
                                        <p class="text-xs text-gray-500">Informasi kontak</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                        2
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">Detail Masalah</p>
                                        <p class="text-xs text-gray-500">Deskripsi masalah</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                        3
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">Verifikasi</p>
                                        <p class="text-xs text-gray-500">CAPTCHA keamanan</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop: Horizontal Steps -->
                            <div class="hidden sm:flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                            1
                                        </div>
                                        <div class="ml-3 text-left">
                                            <p class="text-sm font-semibold text-gray-900">Data Pemohon</p>
                                            <p class="text-xs text-gray-500">Informasi kontak</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 mx-4">
                                    <div class="h-1 bg-gradient-to-r from-green-500 to-gray-300 rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                            2
                                        </div>
                                        <div class="ml-3 text-left">
                                            <p class="text-sm font-semibold text-gray-900">Detail Masalah</p>
                                            <p class="text-xs text-gray-500">Deskripsi masalah</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 mx-4">
                                    <div class="h-1 bg-gradient-to-r from-blue-500 to-gray-300 rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                            3
                                        </div>
                                        <div class="ml-3 text-left">
                                            <p class="text-sm font-semibold text-gray-900">Verifikasi</p>
                                            <p class="text-xs text-gray-500">CAPTCHA keamanan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 1: Informasi Pemohon -->
                        <div class="mb-8 sm:mb-10">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg mr-3 sm:mr-4 flex-shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">Informasi Pemohon</h2>
                                    <p class="text-xs sm:text-sm text-gray-600">Data diri Anda untuk keperluan kontak</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                                <!-- Nama Pemohon -->
                                <div class="group">
                                    <label for="requester_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="requester_name"
                                            id="requester_name"
                                            value="{{ old('requester_name') }}"
                                            required
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm sm:text-base @error('requester_name') border-red-500 @enderror"
                                            placeholder="Masukkan nama lengkap"
                                        >
                                    </div>
                                    @error('requester_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="group">
                                    <label for="requester_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                            </svg>
                                        </div>
                                        <input
                                            type="email"
                                            name="requester_email"
                                            id="requester_email"
                                            value="{{ old('requester_email') }}"
                                            required
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm sm:text-base @error('requester_email') border-red-500 @enderror"
                                            placeholder="contoh@email.com"
                                        >
                                    </div>
                                    @error('requester_email')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Telepon -->
                                <div class="group">
                                    <label for="requester_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon <span class="text-gray-400 font-normal">(Opsional)</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="tel"
                                            name="requester_phone"
                                            id="requester_phone"
                                            value="{{ old('requester_phone') }}"
                                            inputmode="tel"
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm sm:text-base @error('requester_phone') border-red-500 @enderror"
                                            placeholder="08xxxxxxxxxx"
                                        >
                                    </div>
                                    @error('requester_phone')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Departemen -->
                                <div class="group">
                                    <label for="requester_department" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Departemen <span class="text-gray-400 font-normal">(Opsional)</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="requester_department"
                                            id="requester_department"
                                            value="{{ old('requester_department') }}"
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm sm:text-base @error('requester_department') border-red-500 @enderror"
                                            placeholder="Nama departemen"
                                        >
                                    </div>
                                    @error('requester_department')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t-2 border-gray-100 my-8 sm:my-10"></div>

                        <!-- Section 2: Detail Permasalahan -->
                        <div class="mb-8 sm:mb-10">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg mr-3 sm:mr-4 flex-shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">Detail Permasalahan</h2>
                                    <p class="text-xs sm:text-sm text-gray-600">Jelaskan masalah yang Anda alami</p>
                                </div>
                            </div>

                            <div class="space-y-4 sm:space-y-6">
                                <!-- Subjek -->
                                <div class="group">
                                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Subjek Permasalahan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="subject"
                                            id="subject"
                                            value="{{ old('subject') }}"
                                            required
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base @error('subject') border-red-500 @enderror"
                                            placeholder="Ringkasan singkat permasalahan"
                                        >
                                    </div>
                                    @error('subject')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="group">
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Deskripsi Detail <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute top-2.5 sm:top-3 left-3 flex items-start pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                            </svg>
                                        </div>
                                        <textarea
                                            name="description"
                                            id="description"
                                            rows="5"
                                            required
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none text-sm sm:text-base @error('description') border-red-500 @enderror"
                                            placeholder="Jelaskan detail permasalahan yang dialami, kapan mulai terjadi, dan langkah yang sudah dicoba..."
                                        >{{ old('description') }}</textarea>
                                    </div>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Kategori & Prioritas - Stack on mobile -->
                                <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2">
                                    <!-- Kategori -->
                                    <div class="group">
                                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Kategori <span class="text-gray-400 font-normal">(Opsional)</span>
                                        </label>
                                        <div class="relative">
                                            <select
                                                name="category_id"
                                                id="category_id"
                                                class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white text-sm sm:text-base @error('category_id') border-red-500 @enderror"
                                            >
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                        @error('category_id')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Prioritas -->
                                    <div class="group">
                                        <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Prioritas <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select
                                                name="priority"
                                                id="priority"
                                                required
                                                class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white text-sm sm:text-base @error('priority') border-red-500 @enderror"
                                            >
                                                <option value="">-- Pilih Prioritas --</option>
                                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>
                                                    🔴 Critical - Sangat Mendesak
                                                </option>
                                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                                    🟠 High - Tinggi
                                                </option>
                                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                                    🟡 Medium - Sedang
                                                </option>
                                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                                    🟢 Low - Rendah
                                                </option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                        @error('priority')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t-2 border-gray-100 my-8 sm:my-10"></div>

                        <!-- Section 3: Informasi Perangkat -->
                        <div class="mb-8 sm:mb-10">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg mr-3 sm:mr-4 flex-shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">Informasi Perangkat</h2>
                                    <p class="text-xs sm:text-sm text-gray-600">Detail perangkat yang bermasalah <span class="text-gray-400">(Opsional)</span></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2">
                                <!-- Nama Perangkat -->
                                <div class="group">
                                    <label for="asset_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Perangkat
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="asset_name"
                                            id="asset_name"
                                            value="{{ old('asset_name') }}"
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm sm:text-base @error('asset_name') border-red-500 @enderror"
                                            placeholder="Contoh: Laptop Dell Latitude"
                                        >
                                    </div>
                                    @error('asset_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Nomor Seri -->
                                <div class="group">
                                    <label for="asset_serial" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Seri
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="asset_serial"
                                            id="asset_serial"
                                            value="{{ old('asset_serial') }}"
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm sm:text-base @error('asset_serial') border-red-500 @enderror"
                                            placeholder="Nomor seri perangkat"
                                        >
                                    </div>
                                    @error('asset_serial')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Lokasi -->
                                <div class="sm:col-span-2 group">
                                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Lokasi Perangkat
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <input
                                            type="text"
                                            name="location"
                                            id="location"
                                            value="{{ old('location') }}"
                                            class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm sm:text-base @error('location') border-red-500 @enderror"
                                            placeholder="Contoh: Gedung A, Lantai 2, Ruang 201"
                                        >
                                    </div>
                                    @error('location')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t-2 border-gray-100 my-8 sm:my-10"></div>

                        <!-- Section 4: Upload Foto -->
                        <div class="mb-8 sm:mb-10">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg mr-3 sm:mr-4 flex-shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">Upload Foto</h2>
                                    <p class="text-xs sm:text-sm text-gray-600">Foto perangkat yang bermasalah <span class="text-gray-400">(Maksimal 5 foto)</span></p>
                                </div>
                            </div>

                            <!-- Photo Upload Area -->
                            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border-2 border-teal-100">
                                <!-- Upload Buttons -->
                                <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-4">
                                    <!-- Camera Button -->
                                    <label for="camera_input" class="flex flex-col items-center justify-center p-4 sm:p-6 bg-white border-2 border-dashed border-teal-300 rounded-xl cursor-pointer hover:bg-teal-50 hover:border-teal-400 transition-all group">
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-teal-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="mt-2 text-xs sm:text-sm font-semibold text-teal-700">Ambil Foto</span>
                                        <span class="text-xs text-gray-500 hidden sm:block">Kamera Belakang</span>
                                    </label>

                                    <!-- Gallery Button -->
                                    <label for="gallery_input" class="flex flex-col items-center justify-center p-4 sm:p-6 bg-white border-2 border-dashed border-blue-300 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-400 transition-all group">
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="mt-2 text-xs sm:text-sm font-semibold text-blue-700">Pilih Galeri</span>
                                        <span class="text-xs text-gray-500 hidden sm:block">Dari Perangkat</span>
                                    </label>
                                </div>

                                <!-- Hidden file inputs -->
                                <input
                                    type="file"
                                    id="camera_input"
                                    name="photos[]"
                                    accept="image/*"
                                    capture="environment"
                                    multiple
                                    class="hidden"
                                    onchange="handlePhotoUpload(this)"
                                >
                                <input
                                    type="file"
                                    id="gallery_input"
                                    name="photos[]"
                                    accept="image/*"
                                    multiple
                                    class="hidden"
                                    onchange="handlePhotoUpload(this)"
                                >

                                <!-- Photo Preview Grid -->
                                <div id="photoPreview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 hidden">
                                    <!-- Previews will be inserted here -->
                                </div>

                                <!-- Photo Count Info -->
                                <div id="photoCountInfo" class="mt-4 text-xs text-gray-600 flex items-center justify-between hidden">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span id="photoCountText">0/5 foto</span>
                                    </span>
                                    <button type="button" onclick="clearAllPhotos()" class="text-red-600 hover:text-red-700 font-medium">
                                        Hapus Semua
                                    </button>
                                </div>

                                @error('photos')
                                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-sm text-red-700 flex items-center">
                                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    </div>
                                @enderror

                                <p class="mt-4 text-xs text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Foto akan dikompres otomatis dan disimpan dalam format WebP
                                </p>
                            </div>
                        </div>

                        <!-- Hidden file inputs for form submission -->
                        <!-- Photo inputs are placed below and triggered by labels above -->

                        <!-- Divider -->
                        <div class="border-t-2 border-gray-100 my-8 sm:my-10"></div>

                        <!-- Section 5: CAPTCHA Verification -->
                        <div class="mb-8 sm:mb-10">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg mr-3 sm:mr-4 flex-shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900">Verifikasi Keamanan</h2>
                                    <p class="text-xs sm:text-sm text-gray-600">Buktikan bahwa Anda adalah manusia</p>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-slate-50 to-blue-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border-2 border-blue-100">
                                <div class="flex flex-col gap-4">
                                    <!-- CAPTCHA Question -->
                                    <div class="flex-shrink-0 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg sm:rounded-xl font-mono text-lg sm:text-xl font-bold shadow-lg select-none text-center">
                                        {{ $captcha['question'] }}
                                    </div>

                                    <!-- CAPTCHA Input & Refresh -->
                                    <div class="flex items-center gap-3">
                                        <input
                                            type="text"
                                            name="captcha"
                                            id="captcha"
                                            value="{{ old('captcha') }}"
                                            required
                                            autocomplete="off"
                                            inputmode="numeric"
                                            class="flex-1 sm:flex-none sm:w-32 px-3 sm:px-4 py-3 sm:py-4 border-2 border-gray-300 rounded-lg sm:rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-mono text-lg sm:text-xl font-bold transition-all @error('captcha') border-red-500 @enderror"
                                            placeholder="?"
                                        >
                                        <a
                                            href="{{ route('repair-requests.create') }}"
                                            class="flex-shrink-0 p-2.5 sm:p-3 bg-white hover:bg-gray-50 border-2 border-gray-200 hover:border-gray-300 rounded-lg sm:rounded-xl transition-all shadow-sm hover:shadow-md group"
                                            title="Ganti Soal CAPTCHA"
                                        >
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 group-hover:rotate-180 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                @error('captcha')
                                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-sm text-red-700 flex items-center">
                                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    </div>
                                @enderror

                                <p class="mt-4 text-xs text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Jawab pertanyaan matematika di atas untuk memverifikasi bahwa Anda adalah manusia
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button - Mobile Full Width -->
                        <div class="pt-6 sm:pt-8 border-t-2 border-gray-100">
                            <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                                <a href="{{ url('/') }}" class="inline-flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors group">
                                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    <span>Kembali ke Beranda</span>
                                </a>
                                <button
                                    type="submit"
                                    class="group relative w-full sm:w-auto px-8 sm:px-10 py-3.5 sm:py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold text-base sm:text-lg rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                >
                                    <span class="relative z-10 flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        <span>Kirim Permintaan</span>
                                    </span>
                                    <div class="absolute inset-0 bg-gradient-to-r from-green-700 to-emerald-700 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Cards - Mobile Optimized -->
            <div class="mt-8 sm:mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <!-- Card 1 -->
                <div class="bg-white/80 backdrop-blur-lg rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg border border-white/20 hover:shadow-xl transition-shadow">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Proses Cepat</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Permintaan Anda akan ditinjau oleh IT Manager dalam waktu 1x24 jam</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white/80 backdrop-blur-lg rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg border border-white/20 hover:shadow-xl transition-shadow">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Notifikasi Email</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Anda akan menerima notifikasi melalui email yang terdaftar</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white/80 backdrop-blur-lg rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg border border-white/20 hover:shadow-xl transition-shadow">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Simpan Nomor</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Catat nomor permintaan untuk tracking status permintaan Anda</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative bg-white/80 backdrop-blur-lg border-t border-white/20 mt-8 sm:mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <div class="text-center text-xs sm:text-sm text-gray-600">
                <p>© {{ date('Y') }} <span class="font-semibold">PantauITE</span> - Sistem Manajemen Layanan TI</p>
                <p class="mt-1 text-xs text-gray-500">Form Permintaan Perbaikan • Tanpa Login Diperlukan</p>
            </div>
        </div>
    </footer>

    <!-- Photo Upload JavaScript -->
    <script>
        let photoPreviews = [];
        const MAX_PHOTOS = 5;

        function handlePhotoUpload(input) {
            const files = Array.from(input.files);
            
            // Check if adding these files would exceed the limit
            const currentCount = photoPreviews.length;
            const remainingSlots = MAX_PHOTOS - currentCount;
            
            if (files.length > remainingSlots) {
                alert('Maksimal ' + MAX_PHOTOS + ' foto. Anda dapat menambahkan ' + remainingSlots + ' foto lagi.');
            }

            // Only process up to remaining slots
            const filesToProcess = files.slice(0, remainingSlots);

            filesToProcess.forEach((file, index) => {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('File harus berupa gambar: ' + file.name);
                    return;
                }

                // Validate file size (10MB max before compression)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file maksimal 10MB: ' + file.name);
                    return;
                }

                // Add to previews array
                photoPreviews.push({ file, preview: null });

                // Create preview
                createPreview(file, photoPreviews.length - 1);
            });

            updatePhotoCount();

            // Reset input so same files can be selected again
            input.value = '';
        }

        function createPreview(file, index) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.classList.remove('hidden');

                const div = document.createElement('div');
                div.className = 'relative group aspect-square';
                div.id = 'photo-' + index;

                // Get current timestamp for display
                const now = new Date();
                const timestamp = now.toLocaleString('id-ID', { 
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric',
                    hour: '2-digit', 
                    minute: '2-digit' 
                });

                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg shadow-sm" alt="Preview">
                    <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-xs px-2 py-1 rounded-b-lg flex items-center justify-between">
                        <span class="flex items-center">
                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            ${timestamp}
                        </span>
                    </div>
                    <button type="button" onclick="removePhoto(${index})" class="absolute top-1 right-1 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="absolute top-1 left-1 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-lg">
                        ${index + 1}
                    </div>
                `;

                preview.appendChild(div);
                photoPreviews[index].preview = div;
            };
            reader.readAsDataURL(file);
        }

        function removePhoto(index) {
            photoPreviews[index] = null;
            const element = document.getElementById('photo-' + index);
            if (element) {
                element.remove();
            }
            updatePhotoCount();
        }

        function clearAllPhotos() {
            if (!confirm('Hapus semua foto?')) return;
            
            photoPreviews = [];
            document.getElementById('photoPreview').innerHTML = '';
            document.getElementById('photoPreview').classList.add('hidden');
            document.getElementById('photoCountInfo').classList.add('hidden');
        }

        function updatePhotoCount() {
            const count = photoPreviews.filter(p => p !== null).length;
            const countText = document.getElementById('photoCountText');
            const countInfo = document.getElementById('photoCountInfo');
            
            if (count > 0) {
                countInfo.classList.remove('hidden');
                countText.textContent = count + '/' + MAX_PHOTOS + ' foto';
                
                // Show warning if at max
                if (count >= MAX_PHOTOS) {
                    countText.classList.add('text-red-600', 'font-semibold');
                } else {
                    countText.classList.remove('text-red-600', 'font-semibold');
                }
            } else {
                countInfo.classList.add('hidden');
            }
        }

        // Drag and drop support
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.querySelector('.bg-gradient-to-br.from-teal-50');
            if (!uploadArea) return;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadArea.classList.add('border-teal-400', 'bg-teal-100');
            }

            function unhighlight(e) {
                uploadArea.classList.remove('border-teal-400', 'bg-teal-100');
            }

            uploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                // Use gallery input to handle the files
                const input = document.getElementById('gallery_input');
                input.files = files;
                handlePhotoUpload(input);
            }
        });
    </script>
</div>
@endsection

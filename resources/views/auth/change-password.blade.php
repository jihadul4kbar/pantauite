@extends('layouts.app')

@section('title', __('auth.change_password.page_title'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-yellow-500 to-orange-600 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-2xl">
        <!-- Header -->
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                {{ __('auth.change_password.title') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ Auth::user()->must_change_password ? __('auth.change_password.subtitle') : __('auth.change_password.info_title') }}
            </p>
        </div>

        @if(Auth::user()->must_change_password)
            <div class="rounded-md bg-yellow-50 p-4 border-l-4 border-yellow-400">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            {{ __('auth.change_password.first_login_notice') }}
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>{{ __('auth.change_password.security_notice') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Change Password Form -->
        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700">
                        <ul class="mt-3 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">
                        {{ __('auth.change_password.current_password') }}
                    </label>
                    <input id="current_password" 
                           name="current_password" 
                           type="password" 
                           required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('current_password') border-red-500 @enderror">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('auth.change_password.new_password') }}
                    </label>
                    <input id="password"
                           name="password"
                           type="password"
                           required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('auth.change_password.password_hint') }}
                    </p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        {{ __('auth.change_password.confirm_password') }}
                    </label>
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('auth.change_password.submit') }}
                </button>
            </div>

            @if(!Auth::user()->must_change_password)
                <div class="text-center">
                    <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        {{ __('auth.change_password.back_to_dashboard') }}
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

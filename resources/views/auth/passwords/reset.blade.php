@extends('layouts.app')

@section('content')

<div class="w-full h-screen flex items-center justify-center navbar-offset">
    <form method="POST" 
          action="{{ route('password.update') }}" 
          class="w-full md:w-1/2 max-w-xl bg-white lg:border border-cool-gray-300 rounded-lg">

        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="flex font-bold justify-center mb-3 lg:mb-4 lg:mt-12">
            <a>
                <i class="fas fa-car text-7xl text-purple-400"></i>
            </a>
        </div>

        <div class="px-12 pb-10">
            <h2 class="font-bold text-lg mb-5 text-gray-600">Enter and confirm your new password.</h2>

            <!-- Email -->
            <div class="w-full mb-4">
                <div class="flex items-center">
                    <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                    <input type="email" name="email" id="email"
                        placeholder="New Password"
                        class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none"
                        value="{{ $email ?? old('email') }}">
                </div>
                @error('email')
                    <span class="text-red-500 text-xs italic my-2" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Password -->
            <div class="w-full mb-4">
                <div class="flex items-center">
                    <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                    <input type="password" name="password" 
                        placeholder="New Password"
                        class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none">
                </div>
                @error('password')
                    <span class="text-red-500 text-xs italic my-2" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="w-full mb-4">
                <div class="flex items-center">
                    <i class='ml-3 fill-current text-purple-400 text-xs z-10 fas fa-user'></i>
                    <input type="password" name="password_confirmation" 
                        placeholder="Confirm Password"
                        class="-mx-6 px-8 w-full border rounded py-2 text-gray-700 focus:outline-none">
                </div>
                @error('password-confirm')
                    <span class="text-red-500 text-xs italic my-2" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Button -->
            <div class="mt-4">
                <button class="text-white font-bold bg-purple-500 hover:bg-purple-400 transition-all 
                            duration-200 focus:outline-none py-2 px-4 w-full"
                        type="submit">
                    Reset Password
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@extends('layouts.auth')

@section('dataform')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <h1>Ingreso al sistema</h1>
        {{-- <div class="social-container">
            <a href="#" class="social"><i class="fa fa-facebook fa-2x"></i></a>
            <a href="#" class="social"><i class="fab fa fa-twitter fa-2x"></i></a>
        </div> --}}
        {{-- <span>or use your account</span> --}}
        
        <!------------- Email Address -------------->
        {{--
        <input type="email" placeholder="Email" />
        --}}
        
        {{-- <x-label for="email" :value="__('Email')" /> --}}

        <x-input id="email" class="block mt-1 w-full" 
                type="email" name="email" :value="old('email')" required autofocus
                placeholder="email" />
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        <!-- Validation Errors -->
        <small class="errorTxt1">
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </small>
        <!-- Password -->
        {{-- <x-label for="password" :value="__('Password')" /> --}}

        <x-input id="password" class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required autocomplete="current-password"
                        placeholder="Password" />
        
        <!-- Remember Me -->
        <div class="col s12 m12 l12 ml-2 mt-1">
            <div class="form-row">
                <label for="remember_me" class="inline-flex items-center ml-5">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Recordar') }}</span>
                </label>
            </div>
        </div>

        {{-- <div class="form-check mb-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Recordar') }}</span>
            </label>
        </div> --}}
        
        
        <x-button class="ml-3">
            {{ __('Ingresar') }}
        </x-button>
        
        @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                {{ __('Olvido su contraseña?') }}
            </a>
        @endif
    </form>
@endsection

@section('logo')
    <img class="img-fluid" src="{{ asset('/img/logo-cpps.png') }}" alt="CPPS" style="width: 200px;">
@stop

@section('info')
    <p>Ingrese sus credenciales (email y contraseña) para acceder al sistema.</p>
@endsection
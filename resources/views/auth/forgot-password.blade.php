@extends('layouts.auth')

@section('dataform')
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <h2>Olvidó su contraseña?</h2>

        <!-- Email Address -->
        <div>
            <x-label for="email" :value="__('Correo electronico')" />

            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        </div>

        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        

        <p>
        </p>

        <div class="flex items-center justify-end mt-4">
            <x-button>
                {{ __('Enviar correo para reseteo de contraseña') }}
            </x-button>
        </div>
    </form>
@endsection

@section('logo')
    {{-- <img class="img-fluid" src="{ { asset('/img/logo-cpps.png') }}" alt="CPPS" style="width: 200px;"> --}}
    <p>
        ZTaller
    </p>
@stop

@section('info')
    <p>
        Olvido su contraseña? No hay problema. Simplemente escriba su correo y se le enviara un email con un link de renovacion de contraseñas.
    </p>
@endsection


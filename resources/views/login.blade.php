@extends('layouts.base')

@section('title', 'Entrar')
@section('content')
    <main class="safe-area min-h-screen flex justify-center items-center">
        <form id="loginForm" method="POST" action="{{route('login.auth')}}" class="max-w-sm w-full bg-slate-100 px-12 py-5 rounded-md flex flex-col gap-3">
            @csrf
            @include('components.logo')

            <div class="flex flex-col">
                <label for="email">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="normal-input @if($errors->has('email') || $errors->has('generalError')) input-error @endif"
                    placeholder="Digite seu email..."
                    value="{{old('email')}}"
                    required>

                @if($errors->has('email'))
                    <p class="error-message">{{$errors->first('email')}}</p>
                @endif
            </div>
            <div class="flex flex-col">
                <label for="password">Senha</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="normal-input @if($errors->has('password') || $errors->has('generalError')) input-error @endif"
                    placeholder="Digite sua senha..."
                    required>

                @if($errors->has('password'))
                    <p class="error-message">{{$errors->first('password')}}</p>
                @endif
            </div>
            @if($errors->has('generalError'))
                <p class="error-message">{{$errors->first('generalError')}}</p>
            @endif

            <div class="mt-4">
                <button type="submit" id="loginBtn" class="normal-btn w-full">Entrar</button>
            </div>
        </form>
    </main>
@endsection

@push('head-scripts')
    @vite('resources/js/login.js')
@endpush

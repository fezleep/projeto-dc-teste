<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

<div class="d-flex justify-content-between align-items-center mt-4">
    @if (Route::has('password.request'))
        <a class="text-sm text-decoration-underline text-muted" href="{{ route('password.request') }}">
            {{ __('Esqueceu sua senha?') }}
        </a>
    @endif

    {{-- SUBSTITUÍMOS <x-button> por um botão Bootstrap --}}
    <button type="submit" class="btn btn-primary ms-4">
        {{ __('Entrar') }}
    </button>
</div>

{{-- ADICIONE ESTE BLOCO ABAIXO (ajustado para Bootstrap) --}}
<div class="text-center mt-4">
    <p class="text-sm text-muted">
        Ainda não tem uma conta?
        {{-- SUBSTITUÍMOS <x-nav-link> por um link Bootstrap --}}
        <a class="text-sm text-decoration-underline text-muted" href="{{ route('register') }}">
            {{ __('Registre-se aqui') }}
        </a>
    </p>
</div>
    </form>
</x-guest-layout>

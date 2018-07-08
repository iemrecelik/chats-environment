@extends('base.index')

@section('contents')
<div id="auth">
  <h3 class="text-white">{{ session('loginTime') }}</h3>
  <auth-component
  :pppage="'login'"
  :pproutes="{
    'login': '{{ route('login') }}',
    'register': '{{ route('register') }}',
    'reset': '{{ route('password.request') }}',
  }"
  :pplangs="{
    'password': '{{ __('Password') }}',
    'email': '{{ __('E-Mail') }}',
    'login': '{{ __('Login') }}',
    'register': '{{ __('Register') }}',
    'resetPassword': '{{ __('Forgot Your Password?') }}',
  }"
  :pperrors="{{ count($errors) > 0?$errors:'{}' }}"
  :ppexistauth="'{{ session('existAuth') ?? '' }}'"
  :ppold="{
    'email': '{{ old('email') }}',
    'password': '{{ old('password') }}'
  }"
  >
  </auth-component>
</div>
@endsection
@extends('base.index')

@section('contents')
<div id="auth">
  <auth-component
  :pppage="'register'"
  :pproutes="{
    'login': '{{ route('login') }}',
    'register': '{{ route('register') }}',
  }"
  :pplangs="{
    'password': '{{ __('Password') }}',
    'confirmPassword': '{{ __('Confirm Password') }}',
    'email': '{{ __('E-Mail') }}',
    'login': '{{ __('Login') }}',
    'register': '{{ __('Register') }}',
    'registerForm': '{{ __('formAndfield.registerForm') }}',
    'signUp': '{{ __('formAndfield.signUp') }}',
    'name': '{{ __('Name') }}',
    'nickname': '{{ __('formAndfield.nickname') }}',
  }"
  :pperrors="{{ count($errors) > 0?$errors:'{}' }}"
  :ppold="{
    'email': '{{ old('email') }}',
    'name': '{{ old('name') }}',
    'nickname': '{{ old('nickname') }}',
  }"
  >
  </auth-component>
</div>
@endsection
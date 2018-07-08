@extends('base.index')

@section('contents')
<div id="auth">
  <auth-component
  :pppage="'resetPassword'"
  :pproutes="{
    'login': '{{ route('login') }}',
    'register': '{{ route('register') }}',
    'passwordRequest': '{{ route('password.request') }}',
  }"
  :pplangs="{
    'password': '{{ __('Password') }}',
    'confirmPassword': '{{ __('Confirm Password') }}',
    'email': '{{ __('E-Mail') }}',
    'login': '{{ __('Login') }}',
    'register': '{{ __('Register') }}',
    'signUp': '{{ __('formAndfield.signUp') }}',
    'resetPassword': '{{ __('Reset Password') }}',
  }"
  :pperrors="{{ count($errors) > 0?$errors:'{}' }}"
  :ppold="{
    'email': '{{ old('email') }}',
  }"
  >
  </auth-component>
</div>
@endsection
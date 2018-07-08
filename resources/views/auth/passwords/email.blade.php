@extends('base.index')

@section('contents')
<div id="auth">
  <auth-component
  :pppage="'email'"
  :pproutes="{
    'login': '{{ route('login') }}',
    'register': '{{ route('register') }}',
    'passwordEmail': '{{ route('password.email') }}',
  }"
  :pplangs="{
    'email': '{{ __('E-Mail') }}',
    'login': '{{ __('Login') }}',
    'register': '{{ __('Register') }}',
    'resetPassword': '{{ __('Reset Password') }}',
    'signUp': '{{ __('formAndfield.signUp') }}',
    'sendPasswordResetLink': '{{ __('Send Password Reset Link') }}',
  }"
  :pperrors="{{ count($errors) > 0?$errors:'{}' }}"
  :ppold="{
    'email': '{{ old('email') }}',
  }"
  >
  </auth-component>
</div>
@endsection
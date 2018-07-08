@extends('base.index')
@section('navbar')
	<navbar-component></navbar-component>
@endsection
@section('contents')
	<profile-component
	:pproutes="{
		'chats': '{{ route('chats') }}',
		'profile': '{{ route('profile.profile') }}',
		'updateProfile': '{{ route('profile.updateProfile') }}',
		'updateImage': '{{ route('profile.updateImage') }}',
		'changePassword': '{{ route('profile.profile') }}?tab=change-password',
		'changePasswordPost': '{{ route('profile.changePassword') }}',
		'logout': '{{ route('logout') }}',
	}"
	:ppauthuser="{{ json_encode($authUser) }}"
	:pperrors="{{ count($errors) > 0?$errors:'{}' }}"
	:ppsucceed="'{{ session('succeed') }}'"
	:ppcomponent="'{{ session('componentName') }}'"
	:ppoldinput="'{{ json_encode(session()->getOldInput()) }}'"
	>
	</profile-component>
@endsection
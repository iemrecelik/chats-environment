@extends('base.index')
@section('contents')
	@section('container-class', 'container-fluid')
	<chats-component
		:pponlroomscount="{{ json_encode($onlRoomsCount) }}"
		:pponlusersinrooms="{{ json_encode($onlUsersInRooms) }}"
		:pproutes="{
			'chats': '{{ route('chats') }}',
			'profile': '{{ route('profile.profile') }}',
			'changePassword': '{{ route('profile.profile') }}?tab=change-password',
			'logout': '{{ route('logout') }}',
		}"
		:ppauthuser="{{ json_encode($authUser) }}"
	>
	</chats-component>
@endsection
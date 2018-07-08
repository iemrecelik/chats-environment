<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@section('header')
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@show

	<title>@yield('title', 'Chats Application')</title>

	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
</head>
<body>
	
	<div id="app">
		@yield('navbar')
		<div class="main-container @yield('container-class', 'container')">
			@yield('contents')
		</div>	
	</div>

	<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
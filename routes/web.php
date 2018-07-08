<?php

Route::get('/', 'Chats\ChatsController@showChatsEnv')
	->name('chats')
	->middleware('auth.only');

Route::prefix('profile')
	->namespace('Chats')
	->middleware('auth.only')
	->name("profile.")
	->group(function(){
		
		Route::get('/{tab?}', 'ProfileController@showProfile')
			->name('profile');

		Route::get('/?tab={tab}', 'ProfileController@showProfile')
			->name('profileTabChoice');

		Route::post('/update-profile', 'ProfileController@updateProfile')
			->name('updateProfile');

		Route::post('/change-password', 'ProfileController@changePassword')
			->name('changePassword');

		Route::post('/update-image', 'ProfileController@updateImage')
			->name('updateImage');
	});

Auth::routes();

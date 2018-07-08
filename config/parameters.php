<?php

return [
	/*File Upload Image*/
	'filter' => [
		'_1' => [
			'resize' => [185, null, function ($constraint) {
						    $constraint->aspectRatio();
						}],
		],
		'_2' => [
			'resize' => [90, null, function ($constraint) {
						    $constraint->aspectRatio();
						}],
		],
		'_3' => [
			'resize' => [64, null, function ($constraint) {
						    $constraint->aspectRatio();
						}],
		],
		'_4' => [
			'resize' => [50, null, function ($constraint) {
						    $constraint->aspectRatio();
						}],
		]
	],

	/*Image filters array*/
	'userProfileImage' => ['_1', '_2', '_3', '_4'],
];
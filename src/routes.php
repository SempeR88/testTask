<?php

return [
	'~^$~' => [\TaskManager\Controllers\MainController::class, 'main'],
	'~^page_(\d+)$~' => [\TaskManager\Controllers\MainController::class, 'page'],
	'~^tasks/add$~' => [\TaskManager\Controllers\TasksController::class, 'add'],
	'~^tasks/(\d+)/edit$~' => [\TaskManager\Controllers\TasksController::class, 'edit'],
	'~^tasks/(\d+)/delete$~' => [\TaskManager\Controllers\TasksController::class, 'delete'],
	'~^users/login$~' => [\TaskManager\Controllers\UsersController::class, 'login'],
	'~^users/logout~' => [\TaskManager\Controllers\UsersController::class, 'logout']
];
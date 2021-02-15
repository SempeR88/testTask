<?php

try {
	spl_autoload_register(function (string $className) {
		require_once __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php';
	});

	$route = $_GET['route'] ?? ''; 
	$routes = require_once __DIR__ . '/src/routes.php';

	$isRouteFound = false;
	foreach ($routes as $pattern => $controllerAndAction) {
		preg_match($pattern, $route, $matches);
		if (!empty($matches)) {
			$isRouteFound = true;
			break;
		}
	}

	if (!$isRouteFound) {
	    throw new \TaskManager\Exceptions\NotFoundException();
	}

	unset($matches[0]);

	$controllerName = $controllerAndAction[0];
	$actionName = $controllerAndAction[1];

	$controller = new $controllerName();
	$controller->$actionName(...$matches);
} catch (\TaskManager\Exceptions\DbException $e) {
    $view = new \TaskManager\View\View(__DIR__ . '/templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\TaskManager\Exceptions\NotFoundException $e) {
    $view = new \TaskManager\View\View(__DIR__ . '/templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (\TaskManager\Exceptions\UnauthorizedException $e) {
    $view = new \TaskManager\View\View(__DIR__ . '/templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
}
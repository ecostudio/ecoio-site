<?php

/////////////
//SETTINGS //
/////////////

const CONTACT_EMAIL = 'nr@ecostudio.hu';

//////////
//SETUP //
//////////

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/class/loader.php';

$klein = new \Klein\Klein();
$klein->app()->renderer = new MustacheTemplateRenderer(
	'templates',
	'templates/partials',
	[
		'page' => 'templates/partials/pages',
		'hero' => 'templates/partials/heroes'
	]
);
$routes = $klein->app()->routeData = json_decode(file_get_contents('routes.json'), true);
$klein->app()->prg = new FormPersister();
$klein->app()->mailer = Swift_Mailer::newInstance(Swift_SendmailTransport::newInstance());

/////////////////
//BASIC ROUTES //
/////////////////

$klein->respond('GET', '/', function($req, $resp, $service, $app) {
	$routeData = $app->routeData;
	$routeData['about']['active'] = true;

	return $app->renderer->render('about', [
		'routes' => $routeData
	]);
});

foreach ($routes as $id => $data) {
	$klein->respond('GET', $data['url'], function($req, $resp, $service, $app) use ($id, $data) {

		$routeData = $app->routeData;
		$routeData[$id]['active'] = true;

		$errors = $app->prg->getErrors();

		$renderData = [
			'routes' => $routeData,
			'data' => $data,
			'formerrors' => $errors,
			'formsuccess' => $app->prg->getSuccess(),
			'formdata' => count($errors) ? $app->prg->getData() : []
		];

		$app->prg->reset();

		return $app->renderer->render($id, $renderData);
	});
}

////////////////
//ERROR PAGES //
////////////////

$klein->onHttpError(function ($code, $router) {
	$app = $router->app();

	$page = $app->renderer->render(
		$code === 404 ? '404' : 'httperror',
		[
			'routes' => $app->routeData,
			'data' => [ 'code' => $code ]
		]
	);

	$router->response()->body($page);
});

/////////////////
//CONTACT FORM //
/////////////////

$klein->respond('POST', $routes['contact']['url'], function ($req, $resp, $service, $app) {

	$form = new KleinContactForm($service, $req->params(), [
		'name' => function ($v) { $v->notNull(); },
		'email2' => function ($v) { $v->notNull()->isEmail(); },
		'message' => function ($v) { $v->notNull(); },
	]);

	$honeypot = $req->param('email');
	if ($form->isValid() && empty($honeypot)) {
		$message = Swift_Message::newInstance()
			->setSubject('Form üzenet - ' . $request->param('referrer'))
			->setFrom($request->param('email2'))
			->setTo(CONTACT_EMAIL)
			->setBody($request->param('message'));
		$app->mailer->send($message);
	}

	if ($req->headers()['X-Requested-With'] === 'XMLHttpRequest') {
		$resp->json($form->errors);
	}
	else {
		$app->prg->store($form->errors, $form->data);
		$service->refresh();
	}
});

////////
//RUN //
////////

$klein->dispatch();

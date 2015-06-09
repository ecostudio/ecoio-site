<?php

/////////////
//SETTINGS //
/////////////

const CONTACT_EMAIL = 'nr@ecostudio.hu';
const UPLOAD_DIR = 'uploads/';

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

	if (in_array($code, [404, 403])) {
		$page = $app->renderer->render(
			$code === 404 ? '404' : 'httperror',
			[
				'routes' => $app->routeData,
				'data' => [ 'code' => $code ]
			]
		);

		$router->response()->body($page);
	}
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
			->setSubject('Form üzenet - ' . $req->param('referrer'))
			->setFrom($req->param('email2'))
			->setTo(CONTACT_EMAIL)
			->setBody($req->param('message'));

		$files = json_decode($req->param('files'));

		foreach($files as $file) {
			$message->attach(Swift_Attachment::fromPath(UPLOAD_DIR . $file));
		}

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

////////////////
//FILE UPLOAD //
////////////////

$klein->respond('POST', '/fileupload', function ($req, $resp, $service, $app) {

	$file = $req->files()['file'];

	if ($file && !$file['error']) {
		$tmpName = $file['tmp_name'];
		$origName = $file['name'];
		$ext = pathinfo($origName, PATHINFO_EXTENSION);
		$size = filesize($tmpName);
		$newName = md5(uniqid()) . '.' . $ext;
		$allowed = ['doc','docx','pdf','xml','jpg','png','gif','jpeg','txt','rtf'];

		if ($size > 2000000) {
			throw Klein\Exceptions\HttpException::createFromCode(413);
		}

		if (!in_array($ext, $allowed)) {
			throw Klein\Exceptions\HttpException::createFromCode(415);
		}

		if (move_uploaded_file($tmpName, UPLOAD_DIR . $newName)) {
			return $newName;
		}
	}

	throw Klein\Exceptions\HttpException::createFromCode(500);
});

////////
//RUN //
////////

$klein->dispatch();

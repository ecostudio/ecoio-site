<?php

/////////////
//SETTINGS //
/////////////

const CONTACT_EMAIL = 'szm@ecostudio.hu';
const UPLOAD_DIR = 'uploads/';
const UPLOAD_ALLOWED_EXTENSIONS = 'doc docx pdf xml jpg jpeg png gif txt rtf';
const UPLOAD_MAX_FILESIZE = 25000000;

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
		'page' => 'templates/partials/pages'
	]
);
$routes = $klein->app()->routeData = json_decode(file_get_contents('routes.json'), true);
$klein->app()->prg = new FormPersister();
$klein->app()->mailer = Swift_Mailer::newInstance(Swift_SendmailTransport::newInstance());
$klein->app()->moddates = [
	'css' => filemtime('css/main.css'),
	'js' => filemtime('js/main.js')
];
$klein->app()->production = !!apache_getenv('PRODUCTION');

/////////////////
//BASIC ROUTES //
/////////////////

$klein->respond('GET', '/', function($req, $resp, $service, $app) {
	$routeData = $app->routeData;
	$routeData['about']['active'] = true;

	return $app->renderer->render('about', [
		'routes' => $routeData,
		'moddates' => $app->moddates,
		'production' => $app->production
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
			'formdata' => count($errors) ? $app->prg->getData() : [],
			'moddates' => $app->moddates,
			'production' => $app->production
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
				'data' => [ 'code' => $code ],
				'moddates' => $app->moddates,
				'production' => $app->production
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
		$msg = 'Telefon:' . $req->param('phone') . "\n\n" . $req->param('message');
		$message = Swift_Message::newInstance()
			->setSubject('Form üzenet - ' . $req->param('referrer'))
			->setFrom([$req->param('email2') => $req->param('name')])
			->setTo(CONTACT_EMAIL)
			->setBody($msg);

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

		if ($size > UPLOAD_MAX_FILESIZE) {
			throw Klein\Exceptions\HttpException::createFromCode(413);
		}

		if (!in_array(strtolower($ext), explode(' ', UPLOAD_ALLOWED_EXTENSIONS))) {
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

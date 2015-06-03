<?php

class FormPersister {

	function __construct() {
		session_start();

		if (!isset($_SESSION['form_persister'])) {
			$_SESSION['form_persister'] = [];
			$this->reset();
		}
	}

	function store($errors, $data) {
		$_SESSION['form_persister'] = [
			'errors' => $errors,
			'data' => $data,
			'success' => count($errors) < 1
		];
	}

	function reset() {
		$_SESSION['form_persister'] = [
			'errors' => [],
			'data' => [],
			'success' => false
		];
	}

	function isEmpty() {
		return (count($_SESSION['form_persister']['errors']) < 1)
			&& !$_SESSION['form_persister']['success'];
	}

	function getErrors() {
		return $_SESSION['form_persister']['errors'];
	}

	function getData() {
		return $_SESSION['form_persister']['data'];
	}

	function getSuccess() {
		return $_SESSION['form_persister']['success'];
	}

}

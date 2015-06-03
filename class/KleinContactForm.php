<?php

class KleinContactForm {

	public $data = [];
	protected $validators = [];
	protected $service;
	protected $valid = false;
	public $errors = [];

	function __construct ($service, $data = [], $validators = []) {
		$this->data = $data;
		$this->validators = $validators;
		$this->service = $service;

		$this->validate();
	}

	function validate() {
		$this->errors = [];

		foreach ($this->validators as $name => $func) {
			try {
				$func($this->service->validateParam($name, $name));
			}
			catch (Klein\Exceptions\ValidationException $e) {
				$this->errors[$name] = $e->getMessage();
			}
		}

		$this->valid = count($this->errors) < 1;
	}

	function isValid() {
		return $this->valid;
	}

}

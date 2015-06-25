<?php

class KleinContactForm {

	public $data = [];
	protected $validators = [];
	protected $service;
	protected $valid = null;
	public $errors = [];

	function __construct ($service, $data = [], $validators = []) {
		$this->data = $data;
		$this->validators = $validators;
		$this->service = $service;
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
		if (is_null($this->valid)) $this->validate();
		return $this->valid;
	}

}

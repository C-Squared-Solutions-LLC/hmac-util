<?php

namespace CSquaredSolutionsLlc\HmacUtil;

class HmacRequest {

	const SUPPORTED_ALGORITHMS = [
		'sha1',
		'sha2',
		'sha256',
		'sha512',
	];

	private $e_request_line;
	private $e_headers;
	private $e_body;

	private $algorithm;

	private $username;
	private $secret;

	public function __setter($name, $value){

		if(method_exists($this,"set" . $this->variableToMethod($name))) {
			$this->{"set" . $this->variableToMethod($name)}($value);
			return;
		}

		$this->e_{$name} = $value;

	}

	public function __getter($name){

		if(method_exists($this,"get" . $this->variableToMethod($name))) {
			return $this->{"get" . $this->variableToMethod($name)}();
		}

		return $this->e_{$name};

	}

	private function variableToMethod($variable_name) {

		return str_replace(" ","",ucwords(str_replace("_", " ",$variable_name)));

	}

	public function setCreds($username, $secret) {

		$this->username = $username;
		$this->secret = $secret;

	}

	public function setAlgorithm(string $algorithm) {

		if(!in_array($algorithm,self::SUPPORTED_ALGORITHMS)) {
			throw new \Exception($algorithm.' is not a supported algorithm');
		}

		$this->algorithm = $algorithm;

	}

	public function getAlgorithm() {

		return $this->algorithm;

	}

	public function getCreds() {

		return [$this->username,$this->secret];

	}

}
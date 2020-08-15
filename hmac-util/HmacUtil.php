<?php

namespace CSquaredSolutionsLlc\HmacUtil;

class HmacUtil {

	public function sign(HmacRequest $hmac_request) {

		// Get Request Line
		$request_line = $hmac_request->request_line;

		// Add X-Date and Content-???? to headers if not there
		$algorithm = $hmac_request->getAlgorithm();
		$headers = $hmac_request->headers;
		$body = $hmac_request->body;

		if(!isset($headers['X-Date'])) {
			$headers['X-Date'] = date('D, d M Y H:i:s T');
		}

		if(!isset($headers['Content-'.strtoupper($algorithm)])) {
			$headers['Content-'.strtoupper($algorithm)] = hash($algorithm,$body);
		}

		// Make header request string
		$header_request_string = $this->makeHeaderRequestString($headers);

		// Make signature string
		$signature_string = $this->makeHeaderSignatureString($request_line,$headers);

		// Hash and encode signature;
		list($username,$secret) = $hmac_request->getCreds();

		$hashed_binary_string = hash_hmac($algorithm,$signature_string,$secret, true);
		$signature = base64_encode($hashed_binary_string);

		// Build Authorization Header
		$headers['Authorization'] = $this->makeAuthorizationHeader($username,$algorithm,$header_request_string,$signature);

		$hmac_request->headers = $headers;

		return $hmac_request;

	}

	private function makeAuthorizationHeader(string $username, string $algorithm, string $header_request_string, string $signature) {

		return 'hmac username="'.$username.'",algorithm="hmac-'.$algorithm.'",headers="'.$header_request_string.'",signature="'.$signature.'"';

	}

	private function makeHeaderRequestString(array $headers) {

		$hrs = "request-line";

		foreach($headers as $name => $value) {

			$hrs .= " ".strtolower($name);

		}

		return $hrs;

	}

	private function makeHeaderSignatureString(string $request_line, array $headers) {

		$signature_string = $request_line . "\n";

		foreach($headers as $name => $value) {

			$signature_string .= strtolower($name).": ".$value."\n";

		}

		return rtrim("\n",$signature_string);

	}

}
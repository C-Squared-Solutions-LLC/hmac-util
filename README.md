# hmac-util

Hmac Util currently supports signing requests with an hmac signature.  You will need to provide the following to the library.
1. HMAC Credentials (Username, Secret)
2. HMAC Algorithm you would like to use
3. Request Line (see HTTP REQUEST STANDARDS https://www.w3.org/Protocols/rfc2616/rfc2616-sec5.html)
4. Body of the request (if any)
5. Headers you wish to sign with (do not include headers you are sending but do not want as part of signature)

## Example of use below

```
<?php

$hmac_request = new CSquaredSolutionsLlc\HmacUtil\HmacRequest();

$hmac_request->setAlgorithm('sha256');
$hmac_request->setCreds("testing","testing");

$hmac_request->request_line = 'POST /test HTTP/1.1';

$hmac_request->body = '{test: 1}';

$hmac_request->headers = [
	'Content-Type' => 'application/json',
	'Content-Length' => 115252
];

$hmac_util = new CSquaredSolutionsLlc\HmacUtil\HmacUtil();

$hmac_request = $hmac_util->sign($hmac_request);
```

After you get the HmacRequest object back, it will contain an authorization header and possibly two more headers for the signature.  Send all headers, plus any others you want, and the body from the HmacRequest object via any restful library that supports it.

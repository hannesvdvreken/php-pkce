# Proof Key for Code Exchange (PKCE) implementation for OAuth Clients

[![Latest Stable Version](https://img.shields.io/packagist/v/d4h/pkce.svg?style=flat-square)](https://packagist.org/packages/d4h/pkce)
[![Total Downloads](https://img.shields.io/packagist/dt/d4h/pkce.svg?style=flat-square)](https://packagist.org/packages/d4h/pkce)
[![License](https://img.shields.io/github/license/hannesvdvreken/php-pkce?style=flat-square)](#license)

Implementation of [RFC 7636](https://tools.ietf.org/html/rfc7636)

## Usage

```php
use function OAuth\PKCE\generatePair;
use function OAuth\PKCE\generateChallenge;
use function OAuth\PKCE\verifyChallenge;

// Generate a pair
$pair = generatePair(128);

// Store this in session
$codeVerifier = $pair->getVerifier();

// Pass this onto the /authorize endpoint of the OAuth server
$codeChallenge = $pair->getChallenge();

$queryString = http_build_query([
    'redirect_uri' => 'https://example.com',
    'response_type' => 'code',
    'client_id' => 'xxxxx',
    'code_challenge_method' => 'S256',
    'code_challenge' => $codeChallenge,
    'state' => $state,
]);

// Use the verifier to exchange the auth code for a token
$params = [
    'client_id' => 'xxxxx',
    'client_secret' => 'xxxxx', // If you have one
    'code' => $code, // Received on your redirect uri
    'code_verifier' => $codeVerifier, // Fetched from the session
];

// On the server side:
if (! verifyChallenge($codeVerifier, $codeChallenge)) {
    // Throw exception because the given code, code_verifier and code_challenge are not matching.
}

// Or if you've saved the code with the code_challenge as a key:
// Query for a stored token with the given code and generated code_challenge
$codeChallenge = generateChallenge($codeVerifier);
```

## Contributing

Feel free to make a pull request. Give a concise but complete description of what is supposed to be added/changed/removed/fixed.

### Testing

To test your code before pushing, run the unit test suite.

```bash
vendor/bin/phpunit
```

## License

[MIT](LICENSE)

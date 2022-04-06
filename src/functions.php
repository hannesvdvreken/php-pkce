<?php

namespace OAuth\PKCE;

use Exception;
use UnexpectedValueException;

if (!function_exists('OAuth\PKCE\generatePair')) {
    /**
     * @throws Exception if an appropriate source of randomness cannot be found
     */
    function generatePair(int $length = 128): Pair
    {
        if ($length < 43 || $length > 128) {
            throw new UnexpectedValueException('Verifier length is supposed to be between 43 and 128 (https://datatracker.ietf.org/doc/html/rfc7636#section-4.1)');
        }

        $chars = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9), ['-', '.', '_', '~']);
        $charsLength = count($chars);

        $verifier = '';

        for ($i = 0; $i < $length; ++$i) {
            $index = random_int(0, $charsLength - 1);
            $verifier .= $chars[$index];
        }

        return new Pair($verifier, generateChallenge($verifier));
    }
}

if (!function_exists('OAuth\PKCE\generateChallenge')) {
    /**
     * @param string $verifier
     *
     * @return string The generated challenge
     */
    function generateChallenge(string $verifier): string
    {
        // https://datatracker.ietf.org/doc/html/rfc7636#section-4.2
        $hash = hash('sha256', $verifier, true);

        //  https://datatracker.ietf.org/doc/html/rfc4648#section-5
        return rtrim(strtr(base64_encode($hash), ['+' => '-', '/' => '_']), '=');
    }
}

if (!function_exists('OAuth\PKCE\verifyChallenge')) {
    /**
     * @param string $verifier
     * @param string $challenge
     *
     * @return bool
     */
    function verifyChallenge(string $verifier, string $challenge): bool
    {
        return $challenge === generateChallenge($verifier);
    }
}

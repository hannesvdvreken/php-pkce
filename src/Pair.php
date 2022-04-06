<?php

namespace OAuth\PKCE;

class Pair
{
    /**
     * @var string
     */
    private $verifier;

    /**
     * @var string
     */
    private $challenge;

    /**
     * @param string $verifier
     * @param string $challenge
     */
    public function __construct(string $verifier, string $challenge)
    {
        $this->verifier = $verifier;
        $this->challenge = $challenge;
    }

    /**
     * @return string
     */
    public function getVerifier(): string
    {
        return $this->verifier;
    }

    /**
     * @return string
     */
    public function getChallenge(): string
    {
        return $this->challenge;
    }
}

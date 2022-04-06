<?php

namespace OAuth\PKCE\Tests;

use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use function OAuth\PKCE\generateChallenge;
use function OAuth\PKCE\generatePair;
use function OAuth\PKCE\verifyChallenge;

class FunctionsTest extends TestCase
{
    public function testHash()
    {
        // Arrange
        $verifier = 'verifier-of-43-characters-long-0123456789ab';

        // Act
        $challenge = generateChallenge($verifier);

        // Assert
        $this->assertSame('4oYcJ-SD07e5wclkXmOPnRHFraHgK6gSjAeuTCm8Mm4', $challenge);
    }

    public function testVerifierFormat()
    {
        // Arrange
        $length = random_int(43, 128);

        // Act
        $pair = generatePair($length);

        // Assert
        $this->assertMatchesRegularExpression('/[A-Za-z0-9-._~]{43,128}/', $pair->getVerifier());
    }

    public function testPair()
    {
        // Arrange
        $length = random_int(43, 128);

        // Act
        $pair = generatePair($length);

        // Assert
        $this->assertSame(generateChallenge($pair->getVerifier()), $pair->getChallenge());
        $this->assertTrue(verifyChallenge($pair->getVerifier(), $pair->getChallenge()));
    }

    public function testExceptionThrownForInvalidLengthArgument()
    {
        // Arrange
        $this->expectException(UnexpectedValueException::class);

        // Act
        generatePair(42);
    }

    public function testExceptionThrownForInvalidLenghtArgument2()
    {
        // Arrange
        $this->expectException(UnexpectedValueException::class);

        // Act
        generatePair(129);
    }

    public function testGenerateLength()
    {
        // Arrange
        $minLength = 43;
        $maxLength = 128;

        // Act
        $pairOne = generatePair($minLength);
        $pairTwo = generatePair($maxLength);

        // Assert
        $this->assertSame($minLength, mb_strlen($pairOne->getVerifier()));
        $this->assertSame($maxLength, mb_strlen($pairTwo->getVerifier()));
    }
}

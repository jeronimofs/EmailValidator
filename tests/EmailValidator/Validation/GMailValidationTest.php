<?php

namespace Egulias\Tests\EmailValidator\Validation;

use Egulias\EmailValidator\EmailLexer;
use Egulias\EmailValidator\Exception\InvalidGMailUser;
use Egulias\EmailValidator\Exception\NoGMailDomain;
use PHPUnit\Framework\TestCase;

class GMailValidationTest extends TestCase
{
    public function validEmailsProvider()
    {
        return [
            ['john@gmail.com'],
            ['john123@gmail.com'],
            ['john.doe@gmail.com'],
            ['john+sub@gmail.com'],
            ['john123+sub@gmail.com'],
            ['john.doe+sub@gmail.com'],
        ];
    }

    public function invalidEmailsProvider()
    {
        return [
            ['john~123@gmail.com'],
            ['john-doe@gmail.com'],
            ['john+sub@protonmail.com'],
            ['john'],
        ];
    }

    /**
     * @dataProvider validEmailsProvider
     */
    public function testValidGMail($validEmail)
    {
        $validation = new GMailValidation();
        $this->assertTrue($validation->isValid($validEmail, new EmailLexer()));
    }

    /**
     * @dataProvider invalidEmailsProvider
     */
    public function testInvalidGMail($invalidEmail)
    {
        $validation = new GMailValidation();
        $this->assertFalse($validation->isValid($invalidEmail, new EmailLexer()));
    }

    public function testNoGMailDomainError()
    {
        $validation = new GMailValidation();
        $expectedError = new NoGMailDomain();
        $validation->isValid("example@invalid.example.com", new EmailLexer());
        $this->assertEquals($expectedError, $validation->getError());
    }

    public function testInvalidGMailUserError()
    {
        $validation = new GMailValidation();
        $expectedError = new InvalidGMailUser();
        $validation->isValid("example-invalid@gmail.com", new EmailLexer());
        $this->assertEquals($expectedError, $validation->getError());
    }
}

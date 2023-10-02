<?php

namespace Egulias\Tests\EmailValidator\Validation;

use Egulias\EmailValidator\EmailLexer;
use Egulias\EmailValidator\Validation\HotmailValidation;
use Egulias\EmailValidator\Exception\NoHotmailDomain;
use Egulias\EmailValidator\Exception\InvalidHotmailUser;

use PHPUnit\Framework\TestCase;

class HotmailValidationTest extends TestCase
{
    public function validEmailsProvider()
    {
        return [
            ['john@hotmail.com'],
            ['john123@hotmail.com'],
            ['john.doe@hotmail.com'],
            ['john-doe@hotmail.com'],
            ['john+sub@hotmail.com'],
            ['john123+sub@hotmail.com'],
            ['john.doe+sub@hotmail.com'],
        ];
    }

    public function invalidEmailsProvider()
    {
        return [
            ['john~123@hotmail.com'],
            ['john!doe@hotmail.com'],
            ['john+sub@protonmail.com'],
            ['john'],
        ];
    }

    /**
     * @dataProvider validEmailsProvider
     */
    public function testValidHotmail($validEmail)
    {
        $validation = new HotmailValidation();
        $this->assertTrue($validation->isValid($validEmail, new EmailLexer()));
    }

    /**
     * @dataProvider invalidEmailsProvider
     */
    public function testInvalidHotmail($invalidEmail)
    {
        $validation = new HotmailValidation();
        $this->assertFalse($validation->isValid($invalidEmail, new EmailLexer()));
    }

    public function testNoHotmailDomainError()
    {
        $validation = new HotmailValidation();
        $expectedError = new NoHotmailDomain();
        $validation->isValid("example@invalid.example.com", new EmailLexer());
        $this->assertEquals($expectedError, $validation->getError());
    }

    public function testInvalidHotmailUserError()
    {
        $validation = new HotmailValidation();
        $expectedError = new InvalidHotmailUser();
        $validation->isValid("example-invalid@gmail.com", new EmailLexer());
        $this->assertEquals($expectedError, $validation->getError());
    }
}

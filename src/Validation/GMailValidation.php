<?php
/**
 * Validates if the e-mail is a valid gmail account.
 * @author Jerônimo Fagundes da Silva <jeronimo.fs@protonmail.com>
 */

namespace Egulias\EmailValidator\Validation;

use Egulias\EmailValidator\EmailLexer;
use Egulias\EmailValidator\Exception\InvalidGMailUser;
use Egulias\EmailValidator\Exception\NoGMailDomain;

class GMailValidation extends RFCValidation
{
    const GMAIL_MIN_USER_LENGTH = 6;
    const GMAIL_MAX_USER_LENGTH = 30;

    public function isValid($email, EmailLexer $emailLexer){
        if (!parent::isValid($email, $emailLexer)) {
            return false;
        }

        list($user, $domain) = explode('@', $email);

        if (strtolower($domain) != 'gmail.com') {
            $this->error = new NoGMailDomain();
            return false;
        }

        if (!preg_match("/^([a-z]|[A-Z]|[0-9]|\.|\+)+$/", $user)) {
            $this->error = new InvalidGMailUser();
            return false;
        }

        $userExplodePlus = explode('+', $user);
        $len = strlen($userExplodePlus[0]);

        if ($len < static::GMAIL_MIN_USER_LENGTH || $len > static::GMAIL_MAX_USER_LENGTH) {
            $this->error = new InvalidGmailUser();
            return false;
        }

        return true;
    }
}
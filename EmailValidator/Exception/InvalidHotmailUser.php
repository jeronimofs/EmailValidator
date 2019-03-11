<?php

namespace Egulias\EmailValidator\Exception;

class InvalidHotmailUser extends InvalidEmail
{
    const CODE = 4072;
    const REASON = "Username part is not valid according to Hotmail's rules";
}

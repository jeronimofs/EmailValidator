<?php

namespace Egulias\EmailValidator\Exception;

class InvalidGMailUser extends InvalidEmail
{
    const CODE = 6006132;
    const REASON = "Username part is not valid according to GMail's rules";
}

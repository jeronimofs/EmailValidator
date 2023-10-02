<?php

namespace Egulias\EmailValidator\Exception;

class NoGMailDomain extends InvalidEmail
{
    const CODE = 6006131;
    const REASON = "Domain is not gmail.com";
}

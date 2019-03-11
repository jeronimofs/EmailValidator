<?php

namespace Egulias\EmailValidator\Exception;

class NoHotmailDomain extends InvalidEmail
{
    const CODE = 4071;
    const REASON = "Domain is not one of hotmail's domains";
}

<?php
/**
 * Validates if the e-mail is a valid hotmail account.
 * @author Jerônimo Fagundes da Silva <jeronimo.fs@protonmail.com>
 */

namespace Egulias\EmailValidator\Validation;

use Egulias\EmailValidator\EmailLexer;
use Egulias\EmailValidator\Exception\InvalidHotmailUser;
use Egulias\EmailValidator\Exception\NoHotmailDomain;

class HotmailValidation extends RFCValidation
{
    const HOTMAIL_MIN_USER_LENGTH = 1;
    const HOTMAIL_MAX_USER_LENGTH = 64;

    /**
     * Returns an array of existing hotmail domains, according to:
     * https://www.internetearnings.com/how-to-register-live-or-hotmail-e-mail-address/
     * https://www.msoutlook.info/question/switch-to-outlookcom-address
     * https://answers.microsoft.com/en-us/outlook_com/forum/all/get-a-country-specific-outlook-email-address-like/6dcb9569-cf7b-41e4-9c78-5f25f9fc43bb
     * https://www.thewindowsclub.com/country-specific-outlook-email-id-domains
     * https://www.tomshardware.com/news/Outlook-New-Domains-International-UK-International-Outlook-address,22106.html
     * @return array All Microsoft Hotmail's domains
     */
    public static function getHotmailDomains() {
        return array(
            // International
            'hotmail.com',
            'microsoft.com',
            'outlook.com',
            'live.com',
            'msn.com',
            'passport.com',
            'passport.net',
            'windowslive.com',

            // Argentina
            'outlook.com.ar',
            'hotmail.com.ar',
            'live.com.ar',

            // Australia
            'outlook.com.au',
            'hotmail.com.au',
            'live.com.au',

            // Austria
            'outlook.at',
            'hotmail.at',
            'live.at',

            // Bahamas
            'hotmail.bs',

            // Belgium
            'outlook.be',
            'hotmail.be',
            'live.be',

            // Brazil
            'outlook.com.br',
            'hotmail.com.br',

            // Canada
            'hotmail.ca',
            'live.ca',

            // Chile
            'outlook.cl',
            'hotmail.cl',
            'live.cl',

            // China
            'live.cn',

            // Czech Republic
            'hotmail.cz',
            'outlook.cz',

            // Denmark
            'outlook.dk',
            'hotmail.dk',
            'live.dk',

            // Finland
            'hotmail.fi',
            'live.fi',

            // France
            'outlook.fr',
            'hotmail.fr',
            'live.fr',

            // Germany
            'outlook.de',
            'hotmail.de',
            'live.de',

            // Greece
            'outlook.com.gr',
            'hotmail.gr',

            // Hong Kong
            'hotmail.com.hk',
            'live.hk',

            // Hungary
            'outlook.hu',
            'hotmail.hu',

            // India
            'outlook.in',
            'live.in',
            'hotmail.co.in',

            // Indonesia
            'outlook.co.id',
            'hotmail.co.id',

            // Ireland
            'outlook.ie',
            'live.ie',

            // Israel
            'outlook.co.il',
            'hotmail.co.il',

            // Italy
            'live.it',
            'outlook.it',
            'hotmail.it',

            // Japan
            'outlook.jp',
            'live.jp',
            'hotmail.co.jp',

            // Korea
            'outlook.kr',
            'live.co.kr',
            'hotmail.co.kr',

            // Latvia
            'outlook.lv',
            'hotmail.lv',

            // Lithuania
            'hotmail.lt',

            // Malaysia
            'outlook.my',
            'hotmail.my',
            'live.com.my',

            // Mexico
            'live.com.mx',

            // Netherlands
            'hotmail.nl',
            'live.nl',

            // New Zealand
            'outlook.co.nz',

            // Norway
            'hotmail.no',
            'live.no',

            // Peru
            'outlook.com.pe',

            // Philippines
            'outlook.ph',
            'hotmail.ph',
            'live.com.ph',

            // Portugal
            'outlook.pt',
            'live.com.pt',

            // Russia
            'live.ru',

            // Saudi Arabia
            'outlook.sa',

            // Serbia
            'hotmail.rs',

            // Singapore
            'outlook.sg',
            'hotmail.sg',
            'live.com.sg',

            // Slovakia
            'outlook.sk',
            'hotmail.sk',

            // South Africa
            'hotmail.co.za',
            'live.co.za',

            // Spain
            'outlook.es',
            'hotmail.es',

            // Sweden
            'hotmail.se',
            'live.se',

            // Taiwan
            'hotmail.com.tw',
            'livemail.tw',

            // Thailand
            'outlook.co.th',
            'hotmail.co.th',

            // Turkey
            'outlook.com.tr',
            'hotmail.com.tr',

            // United Kingdom
            'hotmail.co.uk',
            'live.co.uk,',

            // Vietnam
            'outlook.com.vn',
            'hotmail.com.vn'
        );
    }

    public function isValid($email, EmailLexer $emailLexer){
        if (!parent::isValid($email, $emailLexer)) {
            return false;
        }

        list($user, $domain) = explode('@', $email);

        // Test if domain is in allowed domain list
        if (!in_array(strtolower($domain), static::getHotmailDomains())) {
            $this->error = new NoHotmailDomain();
            return false;
        }

        // Test if it starts with a letter
        if (!preg_match("/^([a-z]|[A-Z]).*$/", $user)) {
            $this->error = new InvalidHotmailUser();
            return false;
        }

        // Test if contains forbidden characters, according to
        // https://support.microsoft.com/en-za/help/2439357/error-message-when-you-try-to-create-a-user-name-that-contains-a-speci
        if (preg_match("/^.*(\~|\!|\#|\\$|\%|\^|\&|\*|\(|\)|\=|\[|\]|\{|\}|\/|\||\;|\:|\"|\<|\>|\?|\,)+.*$/", $user)) {
            $this->error = new InvalidHotmailUser();
            return false;
        }

        // Test if matches allowed sizes
        $userExplodePlus = explode('+', $user);
        $len = strlen($userExplodePlus[0]);

        if ($len < static::HOTMAIL_MIN_USER_LENGTH || $len > static::HOTMAIL_MAX_USER_LENGTH) {
            $this->error = new InvalidHotmailUser();
            return false;
        }

        return true;
    }
}
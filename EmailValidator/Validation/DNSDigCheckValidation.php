<?php
/**
 * Runs the same validation as DNSCheckValidation, but with the option to use a specific resolver.
 * @author Jeronimo Fagundes <jeronimo.fs@protonmail.com>
 */

namespace Egulias\EmailValidator\Validation;

use JeronimoFagundes\PhpDig\Dig;
use JeronimoFagundes\PhpDig\DigConfig;

use Egulias\EmailValidator\Exception\InvalidEmail;
use Egulias\EmailValidator\Warning\NoDNSMXRecord;
use Egulias\EmailValidator\Exception\NoDNSRecord;

class DNSDigCheckValidation extends DNSCheckValidation
{
    /**
     * @var string
     */
    protected $resolver = null; // Machine's resolver as default

    /**
     * DNSDigCheckValidation constructor.
     * @param string $resolver An IP or hostname of a DNS resolver.
     */
    public function __construct($resolver = null)
    {
        parent::__construct();
        $this->resolver = $resolver;
    }

    protected function checkDNS($host)
    {
        $variant = INTL_IDNA_VARIANT_2003;
        if ( defined('INTL_IDNA_VARIANT_UTS46') ) {
            $variant = INTL_IDNA_VARIANT_UTS46;
        }
        $host = rtrim(idn_to_ascii($host, IDNA_DEFAULT, $variant), '.') . '.';

        $digConfig = new DigConfig();
        $digConfig
            ->setServer($this->resolver)
            ->setTimeout(1)
            ->setTries(1);

        $dig = new Dig($digConfig);

        $MXresult = $dig->query($host, 'MX');

        if (empty($MXresult)) {
            $this->warnings[NoDNSMXRecord::CODE] = new NoDNSMXRecord();

            $Aresult = $dig->query($host, 'A');
            $AAAAresult = $dig->query($host, 'AAAA');

            if (empty($Aresult) && empty($AAAAresult)) {
                $this->error = new NoDNSRecord();
            }
        }
        return $MXresult || $Aresult;
    }
}

<?php

namespace DansMaCulotte\Monetico\Resources;

use DansMaCulotte\Monetico\Exceptions\AuthenticationException;

class AuthenticationResource
{
    /** @var array */
    public $details = [];

    /** @var string */
    public $protocol;

    /** @var string */
    public $version;

    /** @var string */
    public $status;

    /** @var array */
    const STATUSES = [
        'authenticated',
        'authentication_not_performed',
        'not_authenticated',
        'authentication_rejected',
        'authentication_attempted',
        'not_enrolled',
        'disabled',
        'error',
    ];

    /** @var string */
    const PROTOCOL = '3DSecure';

    /** @var array */
    const VERSIONS = [
        '1.0.2',
        '2.1.0'
    ];

    /** @var array */
    const LIABILITY_SHIFTS = ['Y', 'N', 'NA'];

    /** @var array */
    const VERES_OPTIONS = ['Y', 'N', 'U'];

    /** @var array */
    const PARES_OPTIONS = ['Y', 'U', 'N', 'A'];

    /** @var array */
    const ARES_OPTIONS = ['Y', 'R', 'C', 'U', 'A', 'N'];

    /** @var array */
    const CRES_OPTIONS = ['Y', 'N'];

    /** @var array */
    const MERCHANT_PREFERENCES = [
        'no_preference',
        'challenge_preferred',
        'challenge_mandated',
        'no_challenge_requested',
        'no_challenge_requested_strong_authentication',
        'no_challenge_requested_trusted_third_party',
        'no_challenge_requested_risk_analysis'
    ];

    /** @var array  */
    const DDDS_STATUSES = [
        -1, 1, 4,
    ];

    /** @var array */
    const DISABLING_REASONS = [
        'commercant',
        'seuilnonatteint',
        'scoring'
    ];

    /**
     * Authentication constructor.
     *
     * @param string $protocol
     * @param string $status
     * @param string $version
     * @param array $details
     * @throws AuthenticationException
     */
    public function __construct(string $protocol, string $status, string $version, array $details)
    {
        $this->protocol = $protocol;
        if ($this->protocol !== self::PROTOCOL) {
            throw AuthenticationException::invalidProtocol($this->protocol);
        }

        $this->status = $status;
        if (!in_array($this->status, self::STATUSES, true)) {
            throw AuthenticationException::invalidStatus($this->status);
        }

        $this->version = $version;
        if (!in_array($this->version, self::VERSIONS, true)) {
            throw AuthenticationException::invalidVersion($this->version);
        }

        $this->setDetails($details);
        $this->setDetailsMessages($details);
    }

    /**
     * @param array $details
     * @throws AuthenticationException
     */
    public function setDetails(array $details)
    {
        if (isset($details['liabilityShift'])) {
            if (!in_array($details['liabilityShift'], self::LIABILITY_SHIFTS, true)) {
                throw AuthenticationException::invalidLiabilityShift($details['liabilityShift']);
            }
            $this->details['liabilityShift'] = $details['liabilityShift'];
        }

        if (isset($details['merchantPreference'])) {
            if (!in_array($details['merchantPreference'], self::MERCHANT_PREFERENCES, true)) {
                throw AuthenticationException::invalidMerchantPreference($details['merchantPreference']);
            }
            $this->details['merchantPreference'] = $details['merchantPreference'];
        }

        if (isset($details['transactionID'])) {
            $this->details['transactionID'] = $details['transactionID'];
        }
        if (isset($details['authenticationValue'])) {
            $this->details['authenticationValue'] = $details['authenticationValue'];
        }

        if (isset($details['status3DS'])) {
            if (!in_array($details['status3DS'], self::DDDS_STATUSES, true)) {
                throw AuthenticationException::invalidDDDSStatus($details['status3DS']);
            }
            $this->details['status3DS'] = $details['status3DS'];
        }

        if (isset($details['disablingReason'])) {
            if (!in_array($details['disablingReason'], self::DISABLING_REASONS, true)) {
                throw AuthenticationException::invalidDisablingReason($details['disablingReason']);
            }
            $this->details['disablingReason'] = $details['disablingReason'];
        }
    }

    /**
     * @param $details
     * @throws AuthenticationException
     */
    private function setDetailsMessages($details)
    {
        if (isset($details['VERes'])) {
            if (!in_array($details['VERes'], self::VERES_OPTIONS, true)) {
                throw AuthenticationException::invalidVERes($details['VERes']);
            }
            $this->details['VERes'] = $details['VERes'];
        }

        if (isset($details['PARes'])) {
            if (!in_array($details['PARes'], self::PARES_OPTIONS, true)) {
                throw AuthenticationException::invalidPARes($details['PARes']);
            }
            $this->details['PARes'] = $details['PARes'];
        }

        if (isset($details['ARes'])) {
            if (!in_array($details['ARes'], self::ARES_OPTIONS, true)) {
                throw AuthenticationException::invalidARes($details['ARes']);
            }
            $this->details['ARes'] = $details['ARes'];
        }

        if (isset($details['CRes'])) {
            if (!in_array($details['CRes'], self::CRES_OPTIONS, true)) {
                throw AuthenticationException::invalidCRes($details['CRes']);
            }
            $this->details['CRes'] = $details['CRes'];
        }
    }
}

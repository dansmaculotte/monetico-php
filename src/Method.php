<?php

namespace DansMaCulotte\Monetico;

abstract class Method
{
    public function generateSeal($securityKey, $fields)
    {
        ksort($fields);

        $query = http_build_query($fields, null, '*');
        $query = urldecode($query);

        return strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));
    }

    public function generateFields($seal, $fields)
    {
        return array_merge(
            $fields,
            ['MAC' => $seal]
        );
    }

    abstract public function validate();
}

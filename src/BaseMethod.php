<?php

namespace DansMaCulotte\Monetico;


trait BaseMethod
{
    public function generateSeal($securityKey, $fields)
    {
        ksort($fields);
        $query = urldecode(http_build_query($fields, null, '*'));

        return strtoupper(hash_hmac(
            'sha1',
            $query,
            $securityKey
        ));
    }

    public function generateFields($seal, $fields) {
        return array_merge(
            $fields,
            ['MAC' => $seal]
        );
    }
}
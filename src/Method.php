<?php

namespace DansMaCulotte\Monetico;

interface Method
{
    public function generateSeal($securityKey, $fields);

    public function generateFields($seal, $fields);

    public function validate();
}

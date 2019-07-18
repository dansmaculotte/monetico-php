<?php

namespace DansMaCulotte\Monetico;


interface iMethod
{
    public function generateSeal($securityKey, $fields);

    public function generateFields($seal, $fields);

    public function validate();
}
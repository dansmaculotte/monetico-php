<?php

namespace DansMaCulotte\Monetico;


interface method
{
    public function generateSeal($securityKey, $fields);

    public function generateFields($seal, $fields);

    public function validate();
}
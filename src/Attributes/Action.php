<?php

/*
    WeRtOG
    FoxyMVC
*/
namespace WeRtOG\FoxyMVC\Attributes;

use Attribute;

#[Attribute]
class Action
{
    public string|array|null $RequestMethod;

    public function __construct(string|array|null $RequestMethod = null)
    {
        $this->RequestMethod = $RequestMethod;
    }
}
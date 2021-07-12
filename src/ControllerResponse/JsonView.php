<?php

/*
    WeRtOG
    FoxyMVC
*/
namespace WeRtOG\FoxyMVC\ControllerResponse;

require_once 'Response.php';

class JsonView extends Response
{
    private array $Data;
    public string $MIME = 'application/json';
    public int $ResponseCode = 200;

    public function __construct(array $Data, int $ResponseCode = 200)
    {
        $Data['ok'] = $Data['ok'] ?? true;
        $Data['code'] = $Data['code'] ?? 200;
        $this->Data = $Data;
        $this->ResponseCode = $ResponseCode;
    }

    public function __toString()
    {
        $this->ResponseCode = $this->Data['code'];
        return trim(json_encode($this->Data, JSON_PRETTY_PRINT), "\xEF\xBB\xBF");
    }
}
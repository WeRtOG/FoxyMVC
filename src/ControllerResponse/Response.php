<?php

/*
    WeRtOG
    FoxyMVC
*/
namespace WeRtOG\FoxyMVC\ControllerResponse;

class Response
{
    private string $Data;
    public string $MIME = 'text/plain';
    public int $ResponseCode = 200;

    public function __construct(string $Data, int $ResponseCode = 200)
    {
        $this->Data = $Data;
        $this->ResponseCode = $ResponseCode;
    }

    public function __toString()
    {
        return $this->Data;
    }

    public static function Send(Response $Response): void
    {
        http_response_code($Response->ResponseCode);
        header("Content-Type: $Response->MIME; charset=utf-8");
        echo $Response;
    }
}
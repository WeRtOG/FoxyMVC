<?php

/*
	WeRtOG
	FoxyMVC
*/
namespace WeRtOG\FoxyMVC;

define('FOXYMVC', true);

foreach (glob(__DIR__ . "/*.php") as $Filename) require_once $Filename;
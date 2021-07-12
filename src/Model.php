<?php

/*
	WeRtOG
	FoxyMVC
*/
namespace WeRtOG\FoxyMVC;

require_once 'ModelHelper.php';

class Model
{
	public function __construct(array $Parameters = [])
	{
		ModelHelper::SetParametersFromArray($this, $Parameters);
	}
}
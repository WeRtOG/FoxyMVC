<?php

/*
	WeRtOG
	FoxyMVC
*/
namespace WeRtOG\FoxyMVC;

use WeRtOG\FoxyMVC\ControllerResponse\Response;

/**
 * Класс контроллера
 */
class Controller
{
	
	private ?array $GlobalData;

	public function __construct(array $Models = [], ?array &$GlobalData = null)
	{
		foreach($Models as $ModelName => $Model)
		{
			$this->{$ModelName} = $Model;
		}

		$this->GlobalData = $GlobalData;
	}
	
	public function Index(): Response
	{
		return new Response('Not implemented.');
	}
}
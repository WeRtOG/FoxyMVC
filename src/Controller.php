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

	public function __construct(array $Models = [])
	{
		foreach($Models as $ModelName => $Model)
		{
			$this->{$ModelName} = $Model;
		}
	}
	
	public function Index(): Response
	{
		return new Response('Not implemented.');
	}
}
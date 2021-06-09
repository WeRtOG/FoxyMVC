<?php
    /*
        WeRtOG
        FoxyMVC
    */
	namespace WeRtOG\FoxyMVC;

	foreach (glob(__DIR__ . "/ControllerResponse/*.php") as $Filename) require_once $Filename;

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
?>
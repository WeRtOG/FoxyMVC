<?php

/*
	WeRtOG
	FoxyMVC
*/
namespace WeRtOG\FoxyMVC\ControllerResponse;

require_once 'Response.php';

use WeRtOG\FoxyMVC\Route;


class View extends Response
{
	public string $MIME = 'text/html';
	public int $ResponseCode = 200;
	
	public array $GlobalData;

	public ?string $Root = null;
	public ?string $Route = null;

	private string $ContentView;
	private string $PageTitle;
	private ?string $TemplateView;
	private ?array $Data;


	public function __construct(string $ContentView, string $PageTitle, ?string $TemplateView = null, ?array $Data = null, int $ResponseCode = 200)
	{
		$this->ContentView = $ContentView;
		$this->PageTitle = $PageTitle;
		$this->TemplateView = $TemplateView;
		$this->Data = $Data;

		$this->ResponseCode = $ResponseCode;
	}

	public function LoadCSS(string $Path)
	{
		$FilePublicPath = Route::GetProjectRoot() . str_replace(FOXYMVC_ROOT_PATH, '', $Path);
		$FilePublicPath = str_replace('\\', '/', $FilePublicPath);

		echo '<link rel="stylesheet" href="' . $FilePublicPath . '?v='.filemtime($Path).'">' . PHP_EOL . '	';
	}

	public function LoadJS(string $Path)
	{
		$FilePublicPath = Route::GetProjectRoot() . str_replace(FOXYMVC_ROOT_PATH, '', $Path);
		$FilePublicPath = str_replace('\\', '/', $FilePublicPath);

		echo '<script src="' . $FilePublicPath . '?v='.filemtime($Path).'"></script>' . PHP_EOL;
	}
	
	public function __toString()
	{
		if(defined('FOXYMVC_ROOT_PATH'))
		{
			$this->Root = Route::GetRoot();
			$this->Route = Route::GetRoute();
		}

		ob_start();
		include $this->TemplateView != null ? $this->TemplateView : $this->ContentView;
		$HTML = ob_get_clean();
		ob_end_flush();

		return $HTML;
	}
}
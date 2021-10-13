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

	public function GenerateFilePublicPath(string $Path, int $PathIntOffset = 0): string
	{
		$ProjectRootPath = str_replace('\\', '/', Route::GetProjectRoot());
		$RootPath = str_replace('\\', '/', FOXYMVC_ROOT_PATH);
		$Path = str_replace('\\', '/', $Path);

		for($i = 0; $i < $PathIntOffset; $i++) {
			$ProjectRootPath = dirname($ProjectRootPath);
			$RootPath = dirname($RootPath);
		}
		
		$FilePublicPath = $ProjectRootPath . str_replace($RootPath, '', $Path);
		$FilePublicPath = str_replace('//', '/', $FilePublicPath);

		return $FilePublicPath . '?v='.filemtime($Path);
	}

	public function LoadCSS(string $Path, int $PathIntOffset = 0): void
	{
		$FilePublicPath = $this->GenerateFilePublicPath($Path, $PathIntOffset);
		echo '<link rel="stylesheet" href="' . $FilePublicPath . '">' . PHP_EOL . '	';
	}

	public function LoadJS(string $Path, int $PathIntOffset = 0): void
	{
		$FilePublicPath = $this->GenerateFilePublicPath($Path, $PathIntOffset);
		echo '<script src="' . $FilePublicPath . '"></script>' . PHP_EOL;
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
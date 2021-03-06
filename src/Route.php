<?php
    /*
        WeRtOG
        FoxyMVC
    */
	namespace WeRtOG\FoxyMVC;

	require_once 'Attributes/Action.php';

	use Exception;
	use ReflectionClass;
	use WeRtOG\FoxyMVC\Attributes\Action as ActionAttribute;
	use WeRtOG\FoxyMVC\ControllerResponse\JsonView;
	use WeRtOG\FoxyMVC\ControllerResponse\Response;
	use WeRtOG\FoxyMVC\ControllerResponse\View;

	class Route
	{
		static function ConnectFolder(string $Path): void
		{
			if(file_exists($Path))
			{
				foreach (glob($Path . "/*.php") as $Filename) include $Filename;
			}
		}

		static function GetRoot(): string
		{
			$Root = str_replace('\\', '/', FOXYMVC_ROOT_PATH);
			$DocumentRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
			
			$Root = str_replace($DocumentRoot, '', $Root);
			return $Root;
		}

		static function CalculatePathIntOffset(string $Path1, string $Path2): int
		{
			$Path1 = str_replace('\\', '/', $Path1);
			$Path2 = str_replace('\\', '/', $Path2);
			
			$ReplaceProbe1 = str_replace($Path2, '', $Path1);
			$ReplaceProbe2 = str_replace($Path1, '', $Path2);
		
			$Offset = $ReplaceProbe1 == $Path1 ? $ReplaceProbe2 : $ReplaceProbe1;
			return count(array_filter(explode("/", $Offset)));
		}

		static function GetProjectRoot()
		{
			$Root = Route::GetRoot();
			return $Root;
		}

		static function GetRoute(): string
		{
			$Root = Route::GetRoot();
			return str_replace($Root, '', $_SERVER['REQUEST_URI']);
		}

		static function Navigate(string $Page): Response
		{
			header('Location: ' . Route::GetRoot() . '/' . $Page);
			exit();
			return new Response('');
		}

		static function NavigateToRoot(): Response
		{
			return self::Navigate('/');
		}

		static function InitializeControllerAction(Controller $Controller, string $ActionName, string $ActionID, string $ControllerName, ?array &$GlobalData = null): void
		{
			$Reflection = new ReflectionClass($ControllerName);
			$ReflectionAction = $Reflection->getMethod($ActionName);
			$ReflectionActionAttributes = $ReflectionAction->getAttributes(ActionAttribute::class);

			$ActionReturnsJson = ($ReflectionAction->getReturnType() ?? null) == JsonView::class;
			define('ActionReturnType', $ActionReturnsJson ? 'JSON' : 'HTML');
			
			$ReflectionActionAttribute = ($ReflectionActionAttributes[0] ?? null)?->newInstance() ?? null;
			
			if($ReflectionActionAttribute instanceof ActionAttribute)
			{
				if($ReflectionActionAttribute->RequestMethod != null) {
					if(
						(
							is_array($ReflectionActionAttribute->RequestMethod) &&
							!in_array(ActionRequestMethod, $ReflectionActionAttribute->RequestMethod)
						) || (
							is_string($ReflectionActionAttribute->RequestMethod) &&
							ActionRequestMethod != $ReflectionActionAttribute->RequestMethod
						)
					)
					{
						if($ActionReturnsJson)
						{
							exit(new JsonView(['ok' => false, 'code' => 400, 'error' => 'Request method is not allowed.']));
						}
						else
						{
							exit(new Response('Request method is not allowed.'));
						}
					}
				}
			}
			else
			{
				Route::ErrorPage404();
			}

			$ControllerResponse = $Controller->$ActionName($ActionID);

			if($ControllerResponse instanceof View)
			{
				$ControllerResponse->GlobalData = &$GlobalData; 
			}
			
			Response::Send($ControllerResponse);
		}

		static function InitializeController(string $ActionName, string $ActionID, string $ControllerName, array $Models = [], ?array &$GlobalData = null): void
		{
			$Controller = new $ControllerName($Models, $GlobalData);
		
			if(method_exists($Controller, $ActionName))
			{
				self::InitializeControllerAction($Controller, $ActionName, $ActionID, $ControllerName, $GlobalData);
			}
			else
			{
				$ActionName = 'Error404';
				if(method_exists($Controller, $ActionName))
				{
					self::InitializeControllerAction($Controller, $ActionName, $ActionID, $ControllerName, $GlobalData);
				}
				else
				{
					Route::ErrorPage404();
				}
			}
		}


		static function Start(string|array $ProjectNamespace, string $ProjectPath, array $Models = [], ?array $GlobalData = null)
		{
			define('FOXYMVC_ROOT_PATH', $ProjectPath);

			$ControllerName = 'Index';
			$ActionName = 'Index';
			$ActionID = 0;

			$Route = Route::GetRoute();
			$Routes = explode('/', $Route);

			if(!empty($Routes[1]))
			{	
				$ControllerName = $Routes[1];
				if($ControllerName == 404)
					$ControllerName = 'Error404';
			}

			if(!empty($Routes[2]))
			{
				$ActionName = $Routes[2];
			}

			if(!empty($Routes[3]))
			{
				$ActionID = $Routes[3];
			}

			define('ActionRequestMethod', $_SERVER['REQUEST_METHOD']);
			define('CurrentMVCController', $ControllerName);
			define('CurrentMVCAction', $ActionName);

			$ControllerName = $ControllerName . 'Controller';
			$ActionName = $ActionName;

			
			if(class_exists(__NAMESPACE__ . '\\' . $ControllerName))
			{
				self::InitializeController($ActionName, $ActionID, __NAMESPACE__ . '\\' . $ControllerName, $Models, $GlobalData);
			}
			else
			{

				if(is_string($ProjectNamespace))
				{
					if(class_exists($ProjectNamespace . '\\' . $ControllerName))
					{
						self::InitializeController($ActionName, $ActionID, $ProjectNamespace . '\\' . $ControllerName, $Models, $GlobalData);
						return;
					}
				}
				else if(is_array($ProjectNamespace))
				{
					foreach($ProjectNamespace as $Namespace)
					{
						if(class_exists($Namespace . '\\' . $ControllerName))
						{
							self::InitializeController($ActionName, $ActionID, $Namespace . '\\' . $ControllerName, $Models, $GlobalData);
							return;
						}
					}
				}
				
				Route::ErrorPage404();
			}
		
		}

		static function ErrorPage404()
		{
			header('HTTP/1.1 404 Not Found');
			header("Status: 404 Not Found");
			Route::Navigate('404');
		}
	}
?>
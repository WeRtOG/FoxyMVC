<?php

/*
    WeRtOG
    FoxyMVC
*/
namespace WeRtOG\FoxyMVC;

require_once 'Exceptions/ModelException.php';

use WeRtOG\FoxyMVC\Exceptions\ModelException;

class ModelHelper
{
    public static function SetParametersFromArray(object $Class, array $Data, bool $SkipCheck = false, bool $MismatchCheck = false)
    {
        foreach($Data as $Key => $Value)
        {
            if(property_exists($Class, $Key) || $SkipCheck)
            {
                $Class->{$Key} = $Value;
            }
        }
        
        if($MismatchCheck)
        {
            $ClassName = get_class($Class);
            if(class_exists($ClassName))
            {
                foreach(get_class_vars($ClassName) as $Key => $Value)
                {
                    if(!isset($Class->{$Key})) throw new ModelException("Field $Key is empty."); 
                }
            }
        }
    }
}
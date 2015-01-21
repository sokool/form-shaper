<?php
namespace MintSoft\FormShaper\StdLib;

/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 09.01.15
 * Time: 08:40
 */
trait StringifyMethodTrait
{

    protected $methodMap = [];

    public function __invoke($string = null, $params = null)
    {
        if ($string === null) {
            return $this;
        }

        $string        = trim($string);
        $hasParam      = strpos($string, '.');
        $methodName    = ($hasParam > 0 ? substr($string, 0, $hasParam) : $string);
        $paramAsString = is_numeric($hasParam) ? substr($string, ++$hasParam) : null;

        $arguments = [];
        if ($hasParam > 0) {
            $arguments[] = $paramAsString;
        }

        return call_user_func_array([$this, $this->mapMethodName($methodName)], array_merge($arguments, array_slice(func_get_args(),1)));
    }

    private function mapMethodName($fakeMethodName)
    {        if (array_key_exists($fakeMethodName, $this->methodMap)) {
            return $this->methodMap[$fakeMethodName];
        }

        return $fakeMethodName;
    }
}
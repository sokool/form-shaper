<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 08.01.15
 * Time: 16:57
 */

namespace MintSoft\FormShaper\Mvc\Controller\Plugin;

use MintSoft\FormShaper\StdLib\StringifyMethodTrait;
use Zend\Mvc\Controller\Plugin\Params as ParamsPlugin;

class Params extends ParamsPlugin
{
    use StringifyMethodTrait;

    function __construct()
    {
        $this->methodMap = [
            'q'      => 'fromQuery',
            'query'  => 'fromQuery',
            'p'      => 'fromPost',
            'post'   => 'fromPost',
            'r'      => 'fromRoute',
            'route'  => 'fromRoute',
            'f'      => 'fromFiles',
            'files'  => 'fromFiles',
            'h'      => 'fromHeader',
            'header' => 'fromHeader',
        ];
    }
}
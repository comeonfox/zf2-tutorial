<?php

namespace Blog;

use Blog\Model\BlogTable;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	public function getServiceConfig()
	{
        return array(
            'factories' => array(
                'Blog\Model\BlogTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new BlogTable($dbAdapter);
                    return $table;
                        },
                    ),
              );
	}
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'blogTidy'=>function($sm){
                    $viewHelper = new \Blog\View\Helper\TidyHtml;
                   return $viewHelper;
                }
        ));
    }
}


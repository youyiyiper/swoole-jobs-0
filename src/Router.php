<?php
namespace Kcloze\Jobs;

class Router
{
    /**
     * 路由入口
     */
    public function run()
    {
        $router = new \Bramus\Router\Router();

        $router->get('/hello', function () {
            echo 'hello,swoole-jobs!';
        });

        $router->get('/pushJobs', function () {
            $object=new \Kcloze\Jobs\Api\Controller\Index();
            $object->push();
        });

        $router->get('/demo', function () {
            $object=new \Kcloze\Jobs\Api\Controller\Index();
            $object->demo();
        });
        
        $router->run();
    }
}

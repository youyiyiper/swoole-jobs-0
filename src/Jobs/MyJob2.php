<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) kcloze <pei.greet@qq.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kcloze\Jobs\Jobs;

class MyJob2
{
    public static function test1($a, $b)
    {
        sleep(1);
        // $client = new \GuzzleHttp\Client();
        // $res    = $client->request('GET', 'https://www.baidu.com', ['timeout' => 3]);
        // echo $res->getStatusCode() . ' test1| title: ' . $a . ' time: ' . $b . PHP_EOL;

        echo ' test1| title: ' . $a . ' time: ' . $b . PHP_EOL;
    }

    public function test2($a, $b, $c)
    {
        #sleep(2);
        // $client         = new \GuzzleHttp\Client();
        // $res            = $client->request('GET', 'https://www.baidu.com', ['timeout' => 3]);
        // echo $res->getStatusCode() . ' test2| title: ' . $a . ' time: ' . $b . ' ' . print_r($c, true) . PHP_EOL;
        file_put_contents(__DIR__ . '/file'.date('Y-m-d').'.log',' test2| title: ' . $a . ' time: ' . $b . ' ' . $c . PHP_EOL,FILE_APPEND);
    }

    public function testError($a, $b)
    {
        //随机故意构造错误，验证子进程推出情况
        $i = mt_rand(0, 5);
        if (3 == $i) {
            echo '出错误了!!!' . PHP_EOL;
            try {
                $this->methodNoFind();
                new Abc();
            } catch (\Exception $e) {
                var_dump($e->getMessage());
            }
        }
    }
}

<?php
namespace Kcloze\Jobs\Api\Services;

use Kcloze\Jobs\JobObject;
use Kcloze\Jobs\Logs;
use Kcloze\Jobs\Queue\BaseTopicQueue;
use Kcloze\Jobs\Queue\Queue;

class PushJobs
{
    /**
     * 数据入队列
     */
    public function pushSimple(string $jobData='')
    {
        if (!$jobData) {
            return \json_encode(['code'=>-3,'message'=>'sorry,jobData params is wrong.','content'=>'']);
        }

        $data=\json_decode($jobData, true);
        $data['topic']=$data['topic']??'';
        $data['jobClass']=$data['jobClass']??'';
        $data['jobMethod']=$data['jobMethod']??'';
        $data['jobParams']=$data['jobParams']??'';
        $data['jobExtras']=$data['jobExtras']??'';
        $data['serializeFunc']=$data['serializeFunc']??'php';

        //检查参数是否有误
        if (!$data['topic'] || !$data['jobClass'] || !$data['jobClass'] || !$data['jobParams']) {
            return \json_encode(['code'=>-2,'message'=>'no,jobData params is wrong.','content'=>$data]);
        }

        $result=$this->push($data['topic'], $data['jobClass'], $data['jobMethod'], $data['jobParams'], $data['jobExtras'], $data['serializeFunc']);
        $data['uuid']=$result;
        
        if ($result) {
            return \json_encode(['code'=>100,'message'=>'ok,job has been pushed success.','content'=>$data]);
        } else {
            return \json_encode(['code'=>-1,'message'=>'sorry,job has been pushed fail.','content'=>$data]);
        }
    }

    /**
     * 插入队列中   将序列化后的数据入队列
     * 
     * Kcloze\Jobs\JobObject Object
        (
            [uuid] => MyJob5cff4703017bd.1560233731.0061
            [topic] => MyJob
            [jobClass] => \Kcloze\Jobs\Jobs\MyJob
            [jobMethod] => test2
            [jobParams] => Array
                (
                    [0] => kcloze
                    [1] => 1560233730
                    [2] => oop
                )

            [jobExtras] => Array
                (
                )

        )
     */
    public function push($topic, $jobClass, $jobMethod, $jobParams=[], $jobExtras=[], $serializeFunc='php')
    {
        //配置文件
        $config        = require SWOOLE_JOBS_ROOT_PATH . '/config.php';
        //日志对象
        $logger        = Logs::getLogger($config['logPath'] ?? '', $config['logSaveFileApp'] ?? '');
        //获取队列对象
        $queue         =Queue::getQueue($config['job']['queue'], $logger);
        $queue->setTopics($config['job']['topics']);
        
        // $jobExtras['delay']    =$delay;
        // $jobExtras['priority'] =BaseTopicQueue::HIGH_LEVEL_1;
        
        //组装对象入队列
        $job           =new JobObject($topic, $jobClass, $jobMethod, $jobParams, $jobExtras);
        $result        =$queue->push($topic, $job, 1, $serializeFunc);
        return $result;
    }
}

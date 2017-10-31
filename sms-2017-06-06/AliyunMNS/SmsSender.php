<?php

namespace AliyunMNS;

use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

class SmsSender
{
    /*
     * 阿里云发送短信.
     *
     * @param string $moblie 手机号 '18500466496'
     * @param string $templateParam array('code'=> '1234', 'product'=> 'orby')
     * @param string $clientId 阿里云accessKey
     * @param string $clientSecret 阿里云accessSecret
     * @param string $signName 短信签名
     * @param string $templateCode 短信模板
     * @param string $endPoint endPoint
     * @param string $topicName 专题名称
     *
     * @return string
     */
    public function send($moblie, $templateParam, $clientId, $clientSecret, $signName, $templateCode, $endPoint, $topicName = "sms.topic-cn-hangzhou")
    {
        /**
         * Step 1. 初始化Client
         */
        $client = new Client($endPoint, $clientId, $clientSecret);
        /**
         * Step 2. 获取主题引用
         */
        $topic = $client->getTopicRef($topicName);
        /**
         * Step 3. 生成SMS消息属性
         */
        // 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
        $batchSmsAttributes = new BatchSmsAttributes($signName, $templateCode);
        // 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
        $batchSmsAttributes->addReceiver($moblie, $templateParam);
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
         $messageBody = "smsmessage";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try {
            $res = $topic->publishMessage($request);

            return $res->getMessageId();
        } catch (MnsException $e) {
            return $e->getMessage();
        }
    }
}

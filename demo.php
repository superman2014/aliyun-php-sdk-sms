<?php

require_once './vendor/autoload.php';

use AliyunMNS\SmsSender;

$a = new SmsSender;
$a->send($moblie, $templateParam, $clientId, $clientSecret, $signName, $templateCode, $endPoint, $topicName = "sms.topic-cn-hangzhou")



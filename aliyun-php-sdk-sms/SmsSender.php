<?php

namespace Superman2014\Aliyun\Sms;

use Superman2014\Aliyun\Sms\Sms\Request\V20160927 as Sms;
use Superman2014\Aliyun\Core\Profile\DefaultProfile;
use Superman2014\Aliyun\Core\DefaultAcsClient;
use Superman2014\Aliyun\Core\Regions\ProductDomain;
use Superman2014\Aliyun\Core\Regions\Endpoint;
use Superman2014\Aliyun\Core\Regions\EndpointProvider;
use Superman2014\Aliyun\Sms\Dysmsapi\Request\V20170525\SendSmsRequest;

$regionIds = ['cn-hangzhou', 'cn-beijing', 'cn-qingdao', 'cn-hongkong', 'cn-shanghai', 'us-west-1', 'cn-shenzhen', 'ap-southeast-1'];
$productDomains = [
    new ProductDomain('Mts', 'mts.cn-hangzhou.aliyuncs.com'),
    new ProductDomain('ROS', 'ros.aliyuncs.com'),
    new ProductDomain('Dm', 'dm.aliyuncs.com'),
    new ProductDomain('Sms', 'sms.aliyuncs.com'),
    new ProductDomain('Bss', 'bss.aliyuncs.com'),
    new ProductDomain('Ecs', 'ecs.aliyuncs.com'),
    new ProductDomain('Oms', 'oms.aliyuncs.com'),
    new ProductDomain('Rds', 'rds.aliyuncs.com'),
    new ProductDomain('BatchCompute', 'batchCompute.aliyuncs.com'),
    new ProductDomain('Slb', 'slb.aliyuncs.com'),
    new ProductDomain('Oss', 'oss-cn-hangzhou.aliyuncs.com'),
    new ProductDomain('OssAdmin', 'oss-admin.aliyuncs.com'),
    new ProductDomain('Sts', 'sts.aliyuncs.com'),
    new ProductDomain('Push', 'cloudpush.aliyuncs.com'),
    new ProductDomain('Yundun', 'yundun-cn-hangzhou.aliyuncs.com'),
    new ProductDomain('Risk', 'risk-cn-hangzhou.aliyuncs.com'),
    new ProductDomain('Drds', 'drds.aliyuncs.com'),
    new ProductDomain('M-kvstore', 'm-kvstore.aliyuncs.com'),
    new ProductDomain('Ram', 'ram.aliyuncs.com'),
    new ProductDomain('Cms', 'metrics.aliyuncs.com'),
    new ProductDomain('Crm', 'crm-cn-hangzhou.aliyuncs.com'),
    new ProductDomain('Ocs', 'pop-ocs.aliyuncs.com'),
    new ProductDomain('Ots', 'ots-pop.aliyuncs.com'),
    new ProductDomain('Dqs', 'dqs.aliyuncs.com'),
    new ProductDomain('Location', 'location.aliyuncs.com'),
    new ProductDomain('Ubsms', 'ubsms.aliyuncs.com'),
    new ProductDomain('Drc', 'drc.aliyuncs.com'),
    new ProductDomain('Ons', 'ons.aliyuncs.com'),
    new ProductDomain('Aas', 'aas.aliyuncs.com'),
    new ProductDomain('Ace', 'ace.cn-hangzhou.aliyuncs.com'),
    new ProductDomain('Dts', 'dts.aliyuncs.com'),
    new ProductDomain('R-kvstore', 'r-kvstore-cn-hangzhou.aliyuncs.com'),
    new ProductDomain('PTS', 'pts.aliyuncs.com'),
    new ProductDomain('Alert', 'alert.aliyuncs.com'),
    new ProductDomain('Push', 'cloudpush.aliyuncs.com'),
    new ProductDomain('Emr', 'emr.aliyuncs.com'),
    new ProductDomain('Cdn', 'cdn.aliyuncs.com'),
    new ProductDomain('COS', 'cos.aliyuncs.com'),
    new ProductDomain('CF', 'cf.aliyuncs.com'),
    new ProductDomain('Ess', 'ess.aliyuncs.com'),
    new ProductDomain('Ubsms-inner', 'ubsms-inner.aliyuncs.com'),
    new ProductDomain('Green', 'green.aliyuncs.com'),
];

$endpoint = new Endpoint('cn-hangzhou', $regionIds, $productDomains);
$endpoints = array($endpoint);
EndpointProvider::setEndpoints($endpoints);

define('ENABLE_HTTP_PROXY', false);
define('HTTP_PROXY_IP', '127.0.0.1');
define('HTTP_PROXY_PORT', '8888');

class SmsSender
{
    /*
     * 阿里云发送短信.
     *
     * @param string $moblie 手机号 '18500466496,13512345678'
     * @param string $paramString {'code': '1234', 'product': 'orby'}
     * @param string $clientId 阿里云accessKey
     * @param string $clientSecret 阿里云accessSecret
     * @param string $signName 短信签名
     * @param string $templateCode 短信模板
     *
     * @throws Superman2014\Aliyun\Core\Exception\ClientException
     * @throws Superman2014\Aliyun\Core\Exception\ServerException;
     *
     * @return string
     */
    public function send($moblie, $paramString, $clientId, $clientSecret, $signName, $templateCode)
    {
        $iClientProfile = DefaultProfile::getProfile('cn-hangzhou', $clientId, $clientSecret);
        $client = new DefaultAcsClient($iClientProfile);
        $request = new Sms\SingleSendSmsRequest();
        $request->setSignName($signName); /*签名名称*/
        $request->setTemplateCode($templateCode); /*模板code*/
        $request->setRecNum($moblie); /*目标手机号*/
        $request->setParamString($paramString); /*模板变量，数字一定要转换为字符串*/

        $response = $client->getAcsResponse($request);

        return $response->RequestId;
    }

    /*
     * 阿里云发送短信.
     *
     * @param string $moblie 手机号 '18500466496,13512345678'
     * @param string $paramString {'code': '1234', 'product': 'orby'}
     * @param string $clientId 阿里云accessKey
     * @param string $clientSecret 阿里云accessSecret
     * @param string $signName 短信签名
     * @param string $templateCode 短信模板
     *
     * @throws Superman2014\Aliyun\Core\Exception\ClientException
     * @throws Superman2014\Aliyun\Core\Exception\ServerException;
     *
     * @return string
     */
    public function sendV2($moblie, $paramString, $clientId, $clientSecret, $signName, $templateCode)
    {
		//短信API产品名
		$product = "Dysmsapi";
		//短信API产品域名
		$domain = "dysmsapi.aliyuncs.com";
        $iClientProfile = DefaultProfile::getProfile('cn-hangzhou', $clientId, $clientSecret);
		DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $client = new DefaultAcsClient($iClientProfile);

		$request = new SendSmsRequest;
		//必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为20个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
		$request->setPhoneNumbers($moblie);
		//这里填你要发送的电话号码
		//必填-短信签名
		$request->setSignName($signName);
		//这里就是刚才让你记住的项目签名
		//必填-短信模板Code
		$request->setTemplateCode($templateCode);
		//这里就是模板CODE
		//选填-假如模板中存在变量需要替换则为必填(JSON格式)
		$request->setTemplateParam($paramString);
		//选填-发送短信流水号
		$request->setOutId("1234");//照填就行了
		//发起访问请求
        $response = $client->getAcsResponse($request);
        var_dump($response);

        return $response->RequestId;
    }
}

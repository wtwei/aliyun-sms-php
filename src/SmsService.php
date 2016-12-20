<?php

namespace Wtwei\AliyunSMS;

require_once __DIR__ . '/../lib/aliyun-php-sdk-sms/aliyun-php-sdk-core/Config.php';

use Sms\Request\V20160927 as SMS;

/**
 * SMS Service
 */
class SmsService
{
	/**
     * [$signName description]
     * @var string
     */
    protected $signName;

    /**
     * [$template description]
     * @var string
     */
    protected $template;

    /**
     * Template variables' data.
     * @var array|null
     */
    protected $params;

    /**
     * @return string
     */
    public function getSignName()
    {
        return $this->signName;
    }

    /**
     * @param string $signName
     */
    public function setSignName($signName)
    {
        $this->signName = $signName;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return array|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array|null $params
     */
    public function setParams($params)
    {
        $this->params = json_encode($params);
    }
	
    /**
     * SMS ACS Client Instance.
     * @var \DefaultAcsClient
     */
    protected $client;

    public function __construct($accessID, $accessKey, $region = 'cn-hangzhou')
    {
        $clientProfile = \DefaultProfile::getProfile($region, $accessID, $accessKey);
        $this->client  = new \DefaultAcsClient($clientProfile);
    }

    public function send($receivers)
    {
        if (is_string($receivers)) {
            $receivers = [$receivers];
        }

        $request = new SMS\SingleSendSmsRequest();
		$request->setSignName($this->getSignName());
		$request->setTemplateCode($this->getTemplate());
		$request->setParamString($this->getParams());
		
		$ret = false;
        foreach ($receivers as $receiver) {
            $request->setRecNum($receiver);
            try {
                $response = $this->client->getAcsResponse($request);
                $ret = true;
            } catch (\ClientException $e) {
                $msg = $e->getErrorMessage() != 'Frequency limit reaches.' ?: '发送过于频繁，请稍候再试';
                throw new SmsException($msg, -1);
            }
        }
		
		return $ret;
    }
}

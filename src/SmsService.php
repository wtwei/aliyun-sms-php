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
    protected $data;

    public function with($data)
    {
        $this->data = $data;
        return $this;
    }

    public function signName($signName)
    {
        $this->signName = $signName;

        return $this;
    }

    public function template($template)
    {
        $this->template = $template;

        return $this;
    }

    public function getParams()
    {
        return json_encode($this->data);
    }

    public function getSignName()
    {
        return $this->signName;
    }

    public function getTemplate()
    {
        return $this->template;
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
                print_r($response);
            } catch (\ClientException $e) {
                throw new SmsException($e->getErrorMessage(), -1);
            }
        }
		
		return $ret;
    }
}

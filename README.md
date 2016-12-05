# aliyun-sms-php

阿里云短信服务 for PHP.

## Installation

    composer require wtwei/aliyun-sms-php

## Usage

### In a general way

```php
$sms = new \Wtwei\AliyunSMS\SmsService('[TEST_APP_ID]', '[TEST_APP_KEY]');

// 设置参数
$sms->setTemplate('[TEMPLATE_NAME]');
$sms->setParams(['code' => '我是验证码', 'product' => '我是产品']);
$sms->setSignName('我是签名');

// 发送短信
$sms->send('[PHONE_NUMBER]');
```


## License

The source code is under [MIT License](https://github.com/wtwei/aliyun-sms-php/blob/master/LICENSE).




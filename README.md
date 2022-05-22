
[<img src="cloud-api.webp?t=1" />](https://supportukrainenow.org)

# WhatsApp   Latest Cloud API Wrapper for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zepson/whatsappcloud-php.svg?style=flat-square)](https://packagist.org/packages/zepson/whatsappcloud-php)
[![Total Downloads](https://img.shields.io/packagist/dt/zepson/whatsappcloud-php.svg?style=flat-square)](https://packagist.org/packages/zepson/whatsappcloud-php)

Opensource python wrapper to WhatsApp Cloud API.


## Features supported

1. Sending messages
2. Sending  Media (images, audio, video and ducuments)
3. Sending location
4. Sending interactive buttons
5. Sending template messages

 
## Installation

You can install the package via composer:

```bash
composer require zepson/whatsappcloud-php
```

## Usage

```php
<?php

require_once 'vendor/autoload.php';
use zepson\Whatsapp\WhatsappClass;

$token = 'YOUR_META_WHATSAPP_APP_ACCESS_TOKEN';
$phone_number_id = '10726082513218961';
//send message
$tsap = new WhatsappClass( $phone_number_id, $token);

$sendtsap = $tsap->send_template('hello_world', '255654485755');
 
print_r($sendtsap);
```
## All Available Methods
#### Send plain text
```php
send_message($message, $recipient_id)
```

#### Send from template
```php
send_template($template, $recipient_id, $lang = "en_US")
```

#### Send Location
```php
 sendLocation($lat, $long, $name, $address, $recipient_id)
```

#### Send image
```php
send_image($image, $recipient_id, $recipient_type = "individual", $caption = null, $link = true)
```
#### Send Audio
```php
  send_audio($audio, $recipient_id, $link = true)
```

#### Send Video
```php
send_video($video, $recipient_id, $caption = null, $link = true)
```

#### Send Document
```php
send_document($document, $recipient_id, $caption = null, $link = true)
```

#### create button 
```php
create_button($button)
```
#### send button
```php

    public function send_button($button, $recipient_id)
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contribute to make more improvement and fix bugs.

 
## Credits

- [Novath Thomas](https://github.com/pro-cms)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

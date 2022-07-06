<?php

require_once 'vendor/autoload.php';
use zepson\Whatsapp\WhatsappClass;

$tsap = new WhatsappClass('phone_number_id', 'YOUR_META_WHATSAPP_APP_ACCESS_TOKEN');

$txt = $tsap->send_template('hello_world', 'phone_number');


//print json format
print_r($txt);

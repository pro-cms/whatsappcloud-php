<?php
require_once 'vendor/autoload.php';
use zepson\Whatsapp\WhatsappClass;

$tsap = new WhatsappClass('10726082513218961','EAAIDcQR5nFQBAGbZCBt8RZBxVyiTmWDSZBaRiZBouJFq11OpggDlTTlqr6IPrtoVsZCMuFkZC3VXvAIp9a1P5shv2X1V7j7m9p1JAcHZCGuDhlHxopUTUJ9nnrw2jEp2JJGO58veTyVKgDiJFkBgCraPDJ4now3JzkMHEjNHgQtZCHlNHEA2nbMPkpoqxtj5BwmRhvi2nfzFJnieyZBoRQsBpFxXDFSibZAzwZD');

$txt = $tsap->send_template('hello_world', '255654485755');


//print json format
print_r($txt);

<?php

namespace zepson\Whatsapp;

class WhatsappClass
{

    private   $client;
    private $phone_number_id;
    private $access_token;
    private $url;


    public function __construct($phone_number_id, $access_token)
    {
        $this->phone_number_id = $phone_number_id;
        $this->access_token = $access_token;
        if (empty($this->phone_number_id) || empty($this->access_token)) {
            throw new \Exception('phone_number_id and access_token are required');
        }
         $this->url = "https://graph.facebook.com/v13.0/{$this->phone_number_id}/messages";
        $this->createClient();
    }

    //create http client
    public function createClient()
    {

        $this->client = new \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->access_token}",
                "Accept" => "application/json"
            ]
        ]);

    }

    //send message
    function send_message($message, $recipient_id, $recipient_type="individual", $preview_url=true) {
        $data = array(
            "messaging_product" => "whatsapp",
            "recipient_type" => $recipient_type,
            "to" => $recipient_id,
            "type" => "text",
            "text" => array("preview_url" => $preview_url, "body" => $message),
        );
        $response = $this->client->post($this->url, ['json' => $data]);
        //get json response
        return $response->getBody();
    }

    //send template
    function send_template($template, $recipient_id, $lang = "en_US") {
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipient_id,
            "type" => "template",
            "template" => ["name" => $template, "language" => ["code" => $lang]],
        ];

        $response = $this->client->post($this->url, ['json' => $data]);
        return  $response->getBody();
    }

    //send Location
    function sendLocation($lat, $long, $name, $address, $recipient_id) {
        $data = array(
            "messaging_product" => "whatsapp",
            "to" => $recipient_id,
            "type" => "location",
            "location" => array(
                "latitude" => $lat,
                "longitude" => $long,
                "name" => $name,
                "address" => $address,
            ),
        );
        $response = $this->client->post($this->url, ['json' => $data]);
        return $response->getBody();
    }
//send image
    function send_image($image, $recipient_id, $recipient_type="individual", $caption=null, $link=true) {
        if ($link) {
            $data = array(
                "messaging_product" => "whatsapp",
                "recipient_type" => $recipient_type,
                "to" => $recipient_id,
                "type" => "image",
                "image" => array(
                    "link" => $image,
                    "caption" => $caption
                )
            );
        } else {
            $data = array(
                "messaging_product" => "whatsapp",
                "recipient_type" => $recipient_type,
                "to" => $recipient_id,
                "type" => "image",
                "image" => array(
                    "id" => $image,
                    "caption" => $caption
                )
            );
        }
        $response = $this->client->post($this->url, ['json' => $data]);
        return $response->getBody();
    }

    //send audio
    function send_audio($audio, $recipient_id, $link=true){
        if($link){
            $data = array(
                "messaging_product" => "whatsapp",
                "to" => $recipient_id,
                "type" => "audio",
                "audio" => array("link" => $audio),
            );
        } else {
            $data = array(
                "messaging_product" => "whatsapp",
                "to" => $recipient_id,
                "type" => "audio",
                "audio" => array("id" => $audio),
            );
        }
        $response = $this->client->post($this->url, ['json' => $data]);
        return $response->getBody();
    }

    //send video

    public function send_video($video, $recipient_id, $caption = null, $link = true)
    {
        if ($link) {
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $recipient_id,
                'type' => 'video',
                'video' => [
                    'link' => $video,
                    'caption' => $caption,
                ],
            ];
        } else {
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $recipient_id,
                'type' => 'video',
                'video' => [
                    'id' => $video,
                    'caption' => $caption,
                ],
            ];
        }

        $response = $this->client->post($this->url, ['json' => $data]);
        return $response->getBody();
    }

    //send document
    function send_document($document, $recipient_id, $caption = null, $link = true) {
        if ($link) {
            $data = array(
                "messaging_product" => "whatsapp",
                "to" => $recipient_id,
                "type" => "document",
                "document" => array("link" => $document, "caption" => $caption),
            );
        } else {
            $data = array(
                "messaging_product" => "whatsapp",
                "to" => $recipient_id,
                "type" => "document",
                "document" => array("id" => $document, "caption" => $caption),
            );
        }
        $response = $this->client->post($this->url, ['json' => $data]);
        return $response->getBody();

    }

    //create button
    function create_button($button) {
        return array(
            "type" => "list",
            "header" => array("type" => "text", "text" => $button->get("header")),
            "body" => array("text" => $button->get("body")),
            "footer" => array("text" => $button->get("footer")),
            "action" => $button->get("action"),
        );
    }

    //send button
    function send_button($button, $recipient_id) {
        $data = array(
            "messaging_product" => "whatsapp",
            "to" => $recipient_id,
            "type" => "interactive",
            "interactive" => $this->create_button($button),
        );
        $response = $this->client->post($this->url, ['json' => $data]);
        return $response->getBody();
    }

    function preprocess($data) {
        return $data["entry"][0]["changes"][0]["value"];
    }

    function get_mobile($data) {
        $data = $this->preprocess($data);
        if (array_key_exists("contacts", $data)) {
            return $data["contacts"][0]["wa_id"];
        }
    }

    function get_name($data) {
        $contact = $this->preprocess($data);
        if ($contact) {
            return $contact["contacts"][0]["profile"]["name"];
        }
    }

    function get_message($data) {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["text"]["body"];
        }
    }

    function get_message_id($data) {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["id"];
        }
    }

    function get_message_timestamp($data) {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["timestamp"];
        }
    }

    function get_interactive_response($data) {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["interactive"]["list_reply"];
        }
    }

    function get_message_type($data) {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["type"];
        }
    }

    function get_delivery($data) {
        $data = $this->preprocess($data);
        if (array_key_exists("statuses", $data)) {
            return $data["statuses"][0]["status"];
        }
    }

    function changed_field($data) {
        return $data["entry"][0]["changes"][0]["field"];
    }


}

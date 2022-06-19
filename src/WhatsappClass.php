<?php

namespace zepson\Whatsapp;

use Exception;
use GuzzleHttp\Client;

class WhatsappClass
{
    /**
     * @var
     */
    private $client = null;
    /**
     * @var
     */
    private $phoneNumberId;
    /**
     * @var
     */
    private $access_token;
    /**
     * @var string
     */
    private $url;

    /**
     * @param string $phoneNumberId
     * @param string $accessToken
     * @param string $version
     * @throws Exception
     */
    public function __construct(string $phoneNumberId, string $accessToken, string $version = "v13.0")
    {
        $this->phoneNumberId = $phoneNumberId;
        $this->access_token = $accessToken;
        if (empty($this->phoneNumberId) || empty($this->access_token)) {
            throw new Exception('phone_number_id and access_token are required');
        }
        $this->url = "https://graph.facebook.com/{$version}/{$this->phoneNumberId}/messages";
        $this->createClient();
    }

    /**
     * @return void
     */
    public function createClient()
    {
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->access_token}",
                "Accept" => "application/json",
            ],
        ]);
    }

    /**
     * @param string $message
     * @param string $recipientId
     * @param string $recipientType
     * @param bool $previewUrl
     * @return mixed
     */
    public function sendMessage(
        string $message,
        string $recipientId,
        string $recipientType = "individual",
        bool $previewUrl = true
    ) {
        $data = [
            "messaging_product" => "whatsapp",
            "recipient_type" => $recipientType,
            "to" => $recipientId,
            "type" => "text",
            "text" => ["preview_url" => $previewUrl, "body" => $message],
        ];
        $response = $this->client->post($this->url, ['json' => $data]);

        return $response->getBody();
    }

    /**
     * @param string $template
     * @param string $recipientId
     * @param string $lang
     * @return mixed
     */
    public function sendTemplate(string $template, string $recipientId, string $lang = "en_US")
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipientId,
            "type" => "template",
            "template" => ["name" => $template, "language" => ["code" => $lang]],
        ];

        $response = $this->client->post($this->url, ['json' => $data]);

        return $response->getBody();
    }

    /**
     * @param string $lat
     * @param string $long
     * @param string $name
     * @param string $address
     * @param string $recipientId
     * @return mixed
     */
    public function sendLocation(string $lat, string $long, string $name, string $address, string $recipientId)
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipientId,
            "type" => "location",
            "location" => [
                "latitude" => $lat,
                "longitude" => $long,
                "name" => $name,
                "address" => $address,
            ],
        ];
        $response = $this->client->post($this->url, ['json' => $data]);

        return $response->getBody();
    }

    /**
     * @param $image
     * @param $recipientId
     * @param string $recipientType
     * @param $caption
     * @param $link
     * @return mixed
     */
    public function sendImage($image, $recipientId, string $recipientType = "individual", $caption = null, $link = true)
    {
        if ($link) {
            $data = [
                "messaging_product" => "whatsapp",
                "recipient_type" => $recipientType,
                "to" => $recipientId,
                "type" => "image",
                "image" => [
                    "link" => $image,
                    "caption" => $caption,
                ],
            ];
        } else {
            $data = [
                "messaging_product" => "whatsapp",
                "recipient_type" => $recipientType,
                "to" => $recipientId,
                "type" => "image",
                "image" => [
                    "id" => $image,
                    "caption" => $caption,
                ],
            ];
        }
        $response = $this->client->post($this->url, ['json' => $data]);

        return $response->getBody();
    }

    /**
     * @param $audio
     * @param $recipientId
     * @param $link
     * @return mixed
     */
    public function sendAudio($audio, $recipientId, $link = true)
    {
        if ($link) {
            $data = [
                "messaging_product" => "whatsapp",
                "to" => $recipientId,
                "type" => "audio",
                "audio" => ["link" => $audio],
            ];
        } else {
            $data = [
                "messaging_product" => "whatsapp",
                "to" => $recipientId,
                "type" => "audio",
                "audio" => ["id" => $audio],
            ];
        }
        $response = $this->client->post($this->url, ['json' => $data]);

        return $response->getBody();
    }

    /**
     * @param $video
     * @param $recipientId
     * @param $caption
     * @param $link
     * @return mixed
     */
    public function sendVideo($video, $recipientId, $caption = null, $link = true)
    {
        if ($link) {
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $recipientId,
                'type' => 'video',
                'video' => [
                    'link' => $video,
                    'caption' => $caption,
                ],
            ];
        } else {
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $recipientId,
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

    /**
     * @param $document
     * @param $recipientId
     * @param $caption
     * @param $link
     * @return mixed
     */
    public function sendDocument($document, $recipientId, $caption = null, $link = true)
    {
        if ($link) {
            $data = [
                "messaging_product" => "whatsapp",
                "to" => $recipientId,
                "type" => "document",
                "document" => ["link" => $document, "caption" => $caption],
            ];
        } else {
            $data = [
                "messaging_product" => "whatsapp",
                "to" => $recipientId,
                "type" => "document",
                "document" => ["id" => $document, "caption" => $caption],
            ];
        }
        $response = $this->client->post($this->url, ['json' => $data]);

        return $response->getBody();
    }

    //create button

    /**
     * @param $button
     * @return array
     */
    public function createButton($button)
    {
        return [
            "type" => "list",
            "header" => ["type" => "text", "text" => $button->get("header")],
            "body" => ["text" => $button->get("body")],
            "footer" => ["text" => $button->get("footer")],
            "action" => $button->get("action"),
        ];
    }

    /**
     * @param $button
     * @param $recipientId
     * @return mixed
     */
    public function sendButton($button, $recipientId)
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $recipientId,
            "type" => "interactive",
            "interactive" => $this->create_button($button),
        ];
        $response = $this->client->post($this->url, ['json' => $data]);

        return $response->getBody();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function preprocess($data)
    {
        return $data["entry"][0]["changes"][0]["value"];
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getMobile($data)
    {
        $data = $this->preprocess($data);
        if (array_key_exists("contacts", $data)) {
            return $data["contacts"][0]["wa_id"];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getName($data)
    {
        $contact = $this->preprocess($data);
        if ($contact) {
            return $contact["contacts"][0]["profile"]["name"];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getMessage($data)
    {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["text"]["body"];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getMessageId($data)
    {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["id"];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getMessageTimestamp($data)
    {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["timestamp"];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getInteractiveResponse($data)
    {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["interactive"]["list_reply"];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getMessageType($data)
    {
        $data = $this->preprocess($data);
        if (array_key_exists("messages", $data)) {
            return $data["messages"][0]["type"];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function getDelivery($data)
    {
        $data = $this->preprocess($data);
        if (array_key_exists("statuses", $data)) {
            return $data["statuses"][0]["status"];
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function changedField($data)
    {
        return $data["entry"][0]["changes"][0]["field"];
    }
}

<?php namespace Lianhua\EasyMailjet;

use Exception;
use Lianhua\Email\Email;
use Lianhua\Email\EmailAddress;

/*
EasyMailjet Library
Copyright (C) 2020  Lianhua Studio

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * @file EasyMailjet.php
 * @author Camille Nevermind
 */

class EasyMailjet
{
    /**
     * @brief The Mailjet API key
     * @var string
     */
    protected $apiKey;

    /**
     * @brief The Mailjet API secret
     * @var mixed
     */
    protected $apiSecret;

    /**
     * @brief The Mailjet object
     * @var \Mailjet\Client
     */
    protected $mailjet;

    /**
     * @brief Sandbox mode
     * @var bool
     */
    protected $sandbox;

    protected $deduplicate;

    /**
     * @brief Converts an EmailAddress to an array
     * @param EmailAddress $address The address to convert
     * @return array The data array
     */
    protected function getAddressData(EmailAddress $address): array
    {
        $data = [];
        $data["Email"] = $address->getAddress();
        $data["Name"] = $address->getName();

        return $data;
    }

    /**
     * @brief Converts an array of EmailAddress to an array of arrays
     * @param array $addresses An array of addresses to convert
     * @return array The data array
     */
    protected function getMultipleAddressesData(array $addresses): array
    {
        $data = [];

        foreach ($addresses as $address) {
            $data[] = $this->getAddressData($address);
        }

        return $data;
    }

    /**
     * @brief Converts a file to an attachment data
     * @param string $file A file to convert
     * @return array The attachment data
     */
    protected function getAttachmentData(string $file): array
    {
        $data = [];

        $data["ContentType"] = mime_content_type($file);
        $data["Filename"] = basename($file);
        $data["Base64Content"] = base64_encode(file_get_contents($file));

        return $data;
    }

    protected function getMultipleAttachmentData(array $files): array
    {
        $data = [];

        foreach ($files as $file) {
            $data[] = $this->getAttachmentData($file);
        }

        return $data;
    }

    /**
     * @brief Sends an email
     * @param Email $email The email to send
     * @param mixed $res The response from the server
     * @return bool True if send succed, false otherwise
     */
    public function sendMail(Email $email, &$res = null, string $campaignId = "", string $messageId = ""): bool
    {
        $data = [];
        $message = [];

        $message ["From"] = $this->getAddressData($email->getFrom());
        $message ["ReplyTo"] = $this->getAddressData($email->getReply());
        $message ["To"] = $this->getMultipleAddressesData($email->getTo());

        if (!empty($email->getCc())) {
            $message ["Cc"] = $this->getMultipleAddressesData($email->getCc());
        }

        if (!empty($email->getBcc())) {
            $message ["Bcc"] = $this->getMultipleAddressesData($email->getBcc());
        }

        $message ["Subject"] = $email->getSubject();
        $message ["TextPart"] = $email->getAlternateContent();
        $message ["HTMLPart"] = $email->getMessage();

        if (!empty($email->getAttachments())) {
            $message["Attachments"] = $this->getMultipleAttachmentData($email->getAttachments());
        }

        $data["Messages"] = [$message];

        if ($this->sandbox) {
            $data["SandboxMode"] = true;
        }

        if ($campaignId !== "") {
            $message["CustomCampaign"] = $campaignId;
        }

        if ($messageId !== "") {
            $message["CustomID"] = $messageId;
        }

        if ($this->deduplicate && $campaignId !== "") {
            $message["DeduplicateCampaign"] = true;
        }

        if (!empty($email->getHeaders())) {
            $message["Headers"] = [];
            $headers = $email->getHeaders();

            foreach ($headers as $header => $value) {
                $message["Headers"][$header] = $value;
            }
        }

        $response = $this->mailjet->post(\Mailjet\Resources::$Email, ["body" => $data]);

        if ($res !== null) {
            $res = $response->getData();
        }

        return $response->success();
    }

    /**
     * @brief Enables or disables sandbox mode
     * @param bool $val The value of the parameter
     * @return void
     *
     * From mailjet: By setting the SandboxMode property to a true value, you will turn off the delivery
     * of the message while still getting back the full range of error messages that could
     * be related to your message processing. If the message is processed without error,
     * the response will follow the normal response payload format,
     * omitting only the MessageID and MessageUUID
     */
    public function setSandbox(bool $val): void
    {
        $this->sandbox = $val;
    }

    /**
     * @brief Emables the DeduplicateCampaign mode
     * @param bool $val The value of the parameter
     * @return void
     *
     * From mailjet: Enables or disables the option to send messages
     * from the same campaign to the same contact multiple times.
     */
    public function setDeduplicate(bool $val): void
    {
        $this->deduplicate = $val;
    }

    /**
     * @brief The constructor
     * @param string $key The public MJ API key
     * @param string $secret The private MJ API key
     * @return void
     * @throws Exception
     */
    public function __construct(string $key, string $secret)
    {
        if (empty($key) || empty($secret)) {
            throw new Exception("Please provide Mailjet API keys");
        }

        $this->apiKey = $key;
        $this->apiSecret = $secret;
        $this->mailjet = new \Mailjet\Client($key, $secret, true, ["version" => "v3.1"]);
        $this->sandbox = false;
    }
}

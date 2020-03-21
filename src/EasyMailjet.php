<?php namespace Lianhua\EasyMailjet;

use Exception;

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

    /**
     * @brief Enables or disables sandbox mode
     * @param bool $val
     * @return void
     *
     * By setting the SandboxMode property to a true value, you will turn off the delivery
     * of the message while still getting back the full range of error messages that could
     * be related to your message processing. If the message is processed without error,
     * the response will follow the normal response payload format,
     * omitting only the MessageID and MessageUUID
     */
    public function setSandbox(bool $val)
    {
        $this->sandbox = $val;
    }

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

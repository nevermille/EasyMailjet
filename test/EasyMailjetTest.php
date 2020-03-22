<?php namespace Lianhua\EasyMailjet\Test;

use Lianhua\EasyMailjet\EasyMailjet;
use PHPUnit\Framework\TestCase;
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
 * @file EasyMailjetTest.php
 * @author Camille Nevermind
 */

class EasyMailjetTest extends TestCase
{
    /**
     * @brief Tests object construction
     * @return void
     */
    public function testConstruct()
    {
        $mj = new EasyMailjet(getenv("MJ_KEY"), getenv("MJ_SECRET"));
        $this->assertNotNull($mj);
    }

    /**
     * @brief Tests a simple mail with only a message
     * @return void
     */
    public function testSendSimpleMail()
    {
        $email = new Email();
        $email->setFrom(new EmailAddress(getenv("LH_MAIL_SENDER"), "Lianhua Studio"));
        $email->addTo(new EmailAddress("test@yopmail.com", "Test"));
        $email->addCc(new EmailAddress("test2@yopmail.com", "Test2"));
        $email->addBcc(new EmailAddress("test3@yopmail.com", "Test3"));
        $email->setMessage("This is an email test by mailjet");
        $email->setSubject("Test message");

        $mj = new EasyMailjet(getenv("MJ_KEY"), getenv("MJ_SECRET"));
        $mj->setSandbox(true);
        $this->assertTrue($mj->sendMail($email, $res));
    }
}

<?php namespace Lianhua\EasyMailjet\Test;

use Lianhua\EasyMailjet\EasyMailjet;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNotNull;

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
 * @file TestEasyMailjet.php
 * @author Camille Nevermind
 */

class TestEasyMailjet extends TestCase
{
    /**
     * @brief Tests object construction
     * @return void
     */
    public function testConstruct()
    {
        $mj = new EasyMailjet(getenv("MJ_KEY"), getenv("MJ_SECRET"));
        assertNotNull($mj);
    }
}

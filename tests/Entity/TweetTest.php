<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Tweet Entity Test
 */
class TweetTest extends TestCase
{

    /**
     * Test tweet getters
     */
    public function testGetters()
    {
        $id = 123;
        $date = new \DateTimeImmutable('2017-01-01');

        $tweet = new Tweet($id, $date);

        $this->assertEquals($id, $tweet->getId());
        $this->assertEquals($date, $tweet->getCreatedAt());
    }
}

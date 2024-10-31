<?php

namespace App\Tests\Entity;

use App\Entity\Album;
use PHPUnit\Framework\TestCase;

class AlbumTest extends TestCase
{
    public function testGetAndSetName()
    {
        $album = new Album();
        $album->setName('Album Name');
        $this->assertEquals('Album Name', $album->getName());
    }
}

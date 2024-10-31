<?php

namespace App\Tests\Entity;

use App\Entity\Media;
use App\Entity\User;
use App\Entity\Album;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    public function testGetAndSetUser()
    {
        $media = new Media();
        $user = new User();
        $media->setUser($user);
        $this->assertSame($user, $media->getUser());
    }

    public function testGetAndSetPath()
    {
        $media = new Media();
        $media->setPath('/uploads/image.jpg');
        $this->assertEquals('/uploads/image.jpg', $media->getPath());
    }

    public function testGetAndSetTitle()
    {
        $media = new Media();
        $media->setTitle('Example Title');
        $this->assertEquals('Example Title', $media->getTitle());
    }

    public function testGetAndSetAlbum()
    {
        $media = new Media();
        $album = new Album();
        $media->setAlbum($album);
        $this->assertSame($album, $media->getAlbum());
    }
}

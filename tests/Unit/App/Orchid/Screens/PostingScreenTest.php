<?php

namespace Laravia\Posting\Tests\Unit\App\Orchid\Screens;

use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Classes\TestScreenCaseTrait;
use Laravia\Posting\App\Orchid\Screens\PostingScreen;

class PostingScreenTest extends TestCase
{

    use TestScreenCaseTrait;
    public function getScreenTestClass()
    {
        return new PostingScreen();
    }

}

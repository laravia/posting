<?php

namespace Laravia\Posting\Tests\Unit\App;

use Laravia\Heart\App\Classes\TestCase;
use Laravia\Posting\App\Posting;

class PostingTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Posting::class);
    }

    public function testGetDashboardMetrics()
    {
        $this->assertIsString(Posting::getDashboardMetrics('postings'));
    }
}

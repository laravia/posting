<?php

namespace Laravia\Posting\Tests\Unit\App\Orchid\Layouts;

use Laravia\Posting\App\Orchid\Layouts\PostingListLayout;
use Laravia\Heart\App\Classes\TestCase;

class PostingListLayoutTest extends TestCase
{

    public $class = 'Laravia\Posting\App\Orchid\Layouts\PostingListLayout';

    public function testInitClass()
    {
        $this->assertClassExist($this->class);
    }

    public function testColumnsExist()
    {
        $this->assertMethodInClassExists('columns', PostingListLayout::class);
    }
    public function testColumns()
    {
        $this->assertIsArray((new PostingListLayout)->columns());
    }
}

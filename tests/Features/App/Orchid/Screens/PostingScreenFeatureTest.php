<?php

namespace Laravia\Posting\Tests\Features\App\Orchid\Screens;

use Laravia\Heart\App\Classes\TestCase;

class PostingScreenFeatureTest extends TestCase
{

    public function test_posting_screen_can_be_rendered()
    {
        $this->actAsAdmin();
        $response = $this->get(route('laravia.posting.list'));
        $response->assertOk();
    }
}

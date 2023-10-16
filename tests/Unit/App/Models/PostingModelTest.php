<?php

namespace Laravia\Posting\Tests\Unit\App;

use Laravia\Posting\App\Models\Posting;
use Laravia\Heart\App\Classes\TestCase;

class PostingModelTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Posting::class);
    }

    public function testCreatePosting()
    {

        $this->assertDatabaseCount('postings', 0);

        $body = $this->faker->word;
        $title = $this->faker->word;
        $onlineFrom = $this->faker->dateTime;
        $onlineTo = $this->faker->dateTime;
        $created_at = $this->faker->dateTime;
        $updated_at = $this->faker->dateTime;
        $active = true;
        $user_id = $this->faker->numberBetween(1, 100);
        $project = $this->faker->word;
        $site = $this->faker->word;
        $element = $this->faker->word;

        Posting::create([
            'body' => $body,
            'title' => $title,
            'onlineFrom' => $onlineFrom,
            'onlineTo' => $onlineTo,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'active' => $active,
            'user_id' => $user_id,
            'project' => $project,
            'site' => $site,
            'element' => $element,
        ]);

        $this->assertDatabaseHas('postings', [
            'body' => $body,
            'title' => $title,
            'active' => $active,
            'user_id' => $user_id,
            'project' => $project,
            'site' => $site,
            'element' => $element,
        ]);
    }
}

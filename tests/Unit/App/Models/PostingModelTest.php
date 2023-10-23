<?php

namespace Laravia\Posting\Tests\Unit\App;

use Laravia\Posting\App\Models\Posting;
use Laravia\Heart\App\Classes\TestCase;

class PostingModelTest extends TestCase
{
    public string $projectName = 'test';

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
        $active = false;
        $user_id = $this->faker->numberBetween(1, 100);
        $project = $this->faker->word;
        $site = $this->faker->word;
        $element = $this->faker->word;

        $posting = Posting::create([
            'body' => $body,
            'title' => $title,
            'onlineFrom' => $onlineFrom,
            'onlineTo' => $onlineTo,
            'active' => $active,
            'user_id' => $user_id,
            'project' => $project,
            'site' => $site,
            'element' => $element,
        ]);

        $this->assertDatabaseHas('postings', [
            'body' => $body,
            'title' => $title,
            'onlineFrom' => $onlineFrom,
            'onlineTo' => $onlineTo,
            'active' => $active,
            'user_id' => $user_id,
            'project' => $project,
            'site' => $site,
            'element' => $element,
        ]);

        $posting->delete();
    }

    /**
     * @dataProvider dataProviderIsOnline
     *
     */
    public function testScopeWhereIsOnline($onlineFrom = null, $onlineTo = null, $active = true, $result = true)
    {

        $body = $this->faker->word;
        $title = $this->faker->word;
        $user_id = $this->faker->numberBetween(1, 100);
        $project = $this->faker->word;
        $site = $this->faker->word;
        $element = $this->faker->word;

        $posting = Posting::create([
            'body' => $body,
            'title' => $title,
            'onlineFrom' => $onlineFrom,
            'onlineTo' => $onlineTo,
            'active' => $active,
            'user_id' => $user_id,
            'project' => $project,
            'site' => $site,
            'element' => $element,
        ]);

        $this->assertEquals(Posting::whereIsOnline()->count(), $result);
        $posting->delete();
    }

    public static function dataProviderIsOnline()
    {
        [
            yield "active" => [
                'onlineFrom' => now()->subDay(),
                'onlineTo' => now()->addDay(),
                'active' => 1,
                'result' => 1
            ],
            yield "not active" => [
                'onlineFrom' => now()->subDay(),
                'onlineTo' => now()->addDay(),
                'active' => 0,
                'result' => 0
            ]
        ];
        [
            yield "online from smaller then carbon now with active true" => [
                'onlineFrom' => now()->subDay(),
                'onlineTo' => now()->addDay(),
                'active' => 1,
                'result' => 1,
            ],
            yield "online from smaller then carbon now with active false" => [
                'onlineFrom' => now()->subDay(),
                'onlineTo' => now()->addDay(),
                'active' => 0,
                'result' => 0,
            ]
        ];
        [
            yield "online from greater then carbon now with active true" => [
                'onlineFrom' => now()->addDay(),
                'onlineTo' => now()->addDay(),
                'active' => 1,
                'result' => 0,
            ],
            yield "online to smaller then carbon now with active true" => [
                'onlineFrom' => now()->subDay(),
                'onlineTo' => now()->subDay(),
                'active' => 1,
                'result' => 0,
            ]
        ];
        [
            yield "online from and online to empty with active true" => [
                'onlineFrom' => null,
                'onlineTo' => null,
                'active' => 1,
                'result' => 1,
            ],
            yield "online from and online to empty with active false" => [
                'onlineFrom' => null,
                'onlineTo' => null,
                'active' => 0,
                'result' => 0,
            ]
        ];
        [
            yield "online from smaller then carbon now and active true" => [
                'onlineFrom' => now()->subDay(),
                'onlineTo' => null,
                'active' => 1,
                'result' => 1,
            ],
            yield "online from smaller then carbon now and active false" => [
                'onlineFrom' => now()->subDay(),
                'onlineTo' => null,
                'active' => 0,
                'result' => 0,
            ]
        ];
        [
            yield "online to greater then carbon now and active true" => [
                'onlineFrom' => now()->addDay(),
                'onlineTo' => null,
                'active' => 1,
                'result' => 0,
            ],
            yield "online to greater then carbon now and active false" => [
                'onlineFrom' => now()->addDay(),
                'onlineTo' => null,
                'active' => 0,
                'result' => 0,
            ]
        ];
        [
            yield "online from null online to greater then carbon now and active true" => [
                'onlineFrom' => null,
                'onlineTo' => now()->addDay(),
                'active' => 1,
                'result' => 1,
            ],
            yield "online from null online to greater then carbon now and active false" => [
                'onlineFrom' => null,
                'onlineTo' => now()->addDay(),
                'active' => 0,
                'result' => 0,
            ]
        ];
        [
            yield "online from null online to smaller then carbon now and active true" => [
                'onlineFrom' => null,
                'onlineTo' => now()->subDay(),
                'active' => 1,
                'result' => 0,
            ],
            yield "online from null online to smaller then carbon now and active false" => [
                'onlineFrom' => null,
                'onlineTo' => now()->subDay(),
                'active' => 0,
                'result' => 0,
            ]
        ];
    }

    public function testGetSearchResultsIdFromCurrentProjectSuccessful()
    {
        $posting_1 = Posting::create([
            'user_id' => '1',
            'body' => 'linux und noch mehr',
            'project' => $this->projectName
        ]);
        $posting_2 = Posting::create([
            'user_id' => '1',
            'body' => 'test',
            'project' => $this->projectName
        ]);

        $this->assertEquals(1, sizeof(Posting::getSearchResultsIdFromCurrentProject('linux', $this->projectName)));
        $posting_1->delete();
        $posting_2->delete();
    }

    public function testGetSearchResultsIdFromCurrentProjectFailedWithNoData()
    {
        $posting_1 = Posting::create([
            'user_id' => '1',
            'body' => 'test',
            'project' => $this->projectName
        ]);
        $posting_2 = Posting::create([
            'user_id' => '1',
            'body' => 'test',
            'project' => $this->projectName
        ]);

        $this->assertNotEquals(2, sizeof(Posting::getSearchResultsIdFromCurrentProject('linux', $this->projectName)));
        $posting_1->delete();
        $posting_2->delete();
    }

    public function testGetCountFromProjectSuccessful()
    {
        $posting_1 = Posting::create([
            'user_id' => '1',
            'body' => 'test',
            'project' => 'testproject'
        ]);
        $posting_2 =  Posting::create([
            'user_id' => '1',
            'body' => 'test',
            'project' => 'testproject'
        ]);

        $this->assertEquals(2, Posting::getCountFromProject('testproject'));
        $posting_1->delete();
        $posting_2->delete();
    }
}

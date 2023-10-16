<?php

use Laravia\Posting\App\Orchid\Screens\PostingScreen;

$config['posting']['links'] = [
    [
        'name' => 'Posting',
        'icon' => 'bs.book',
        'route' => 'laravia.posting.list',
        'title' => __('Packages'),
        'sort' => 2
    ]
];

$config['posting']['dashboard']['metrics'] = [
    'postings' => ['sort' => 30, 'value' => data_get((new PostingScreen())->query(), 'metrics.postings.all'), 'title' => 'Postings'],
];

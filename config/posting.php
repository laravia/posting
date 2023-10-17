<?php

use Laravia\Posting\App\Posting;

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
    'postings' => ['sort' => 30, 'value' => Posting::getDashboardMetrics('postings'), 'title' => 'Postings'],
];

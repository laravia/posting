<?php

namespace Laravia\Posting\App;

use Laravia\Posting\App\Orchid\Screens\PostingScreen;

class Posting
{
    public static function getDashboardMetrics($what): string | false
    {
        try {
            switch ($what) {
                case 'postings':
                    return data_get((new PostingScreen())->query(), 'metrics.postings.all');
                    break;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

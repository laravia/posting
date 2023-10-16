<?php

use Illuminate\Support\Facades\Route;
use Laravia\Posting\App\Orchid\Screens\PostingEditScreen;
use Laravia\Posting\App\Orchid\Screens\PostingScreen;
use Tabuna\Breadcrumbs\Trail;

$prefix = config('platform.prefix');

Route::middleware(['web', 'auth', 'platform'])->group(function () use ($prefix) {

    Route::screen($prefix . '/postings', PostingScreen::class)
        ->name('laravia.posting.list')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Posting');
        });

    Route::screen($prefix . '/posting/{posting?}', PostingEditScreen::class)
        ->name('laravia.posting.edit')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('laravia.posting.list')
            ->push(__('Posting edit or create'), route('laravia.posting.list')));

});

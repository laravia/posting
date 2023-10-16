<?php

namespace Laravia\Posting\App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravia\Heart\App\Traits\ServiceProviderTrait;

class PostingServiceProvider extends ServiceProvider
{
    use ServiceProviderTrait;

    protected $name = "posting";

    public function boot(): void
    {
        $this->defaultBootMethod();
    }
}

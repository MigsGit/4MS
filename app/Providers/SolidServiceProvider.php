<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\FileService;
use App\Services\CommonService;
use App\Services\ResourceService;
use App\Services\PdfCustomService;

use App\Interfaces\FileInterface;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;
use App\Interfaces\PdfCustomInterface;

class SolidServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ResourceInterface::class, ResourceService::class);
        $this->app->bind(CommonInterface::class, CommonService::class);
        $this->app->bind(PdfCustomInterface::class, PdfCustomService::class);
        $this->app->bind(FileInterface::class, FileService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

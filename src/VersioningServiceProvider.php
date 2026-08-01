<?php

declare(strict_types=1);

namespace Rimba\Versioning;

use Filament\Facades\Filament as FacadesFilament;
use Rimba\Base\Services\BitesServiceProvider;
use Rimba\Versioning\Traits\HasVersions;

class VersioningServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        FacadesFilament::serving(function (): void {
            foreach (FacadesFilament::getPanels() as $panel) {
                foreach ($panel->getResources() as $resourceClass) {
                    $model = $resourceClass::getModel();
                    // Check if the underlying Eloquent model uses your Rimba Tree / Bites trait
                    if (in_array(HasVersions::class, class_uses_recursive($model))) {
                    }
                }
            }
        });

    }

    protected function registerPackage(): void
    {
        //
    }
}

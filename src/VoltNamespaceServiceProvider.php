<?php

namespace Ganyicz\VoltNamespace;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Livewire\Livewire;
use Livewire\Volt\ComponentResolver;

class VoltNamespaceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('volt-namespace');
    }

    public function packageRegistered()
    {
        $this->app->singleton(MountedNamespaceDirectories::class);

        $this->app->singleton(\Livewire\Volt\Precompilers\ExtractTemplate::class, ExtractTemplate::class);

        $this->app->bind('view.finder', function ($app) {
            return new FileViewFinder($app['files'], $app['config']['view.paths']);
        });   
    }

    public function packageBooted()
    {
        Livewire::resolveMissingComponent(function (string $name) {
            $segments = explode(':', $name);

            if (count($segments) === 2) {
                return app(ComponentResolver::class)->resolve(
                    $segments[1], collect(app(MountedNamespaceDirectories::class)->paths($segments[0]))->pluck('path')->all(),
                );
            }
        });   
    }
}

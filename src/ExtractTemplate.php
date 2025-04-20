<?php

namespace Ganyicz\VoltNamespace;

use Illuminate\Support\Facades\Blade;
use Livewire\Volt\MountedDirectories;
use Livewire\Volt\Precompilers\ExtractTemplate as Precompiler;

class ExtractTemplate extends Precompiler
{
    /**
     * Create a new precompiler instance.
     */
    public function __construct(
        protected MountedDirectories $mountedDirectories,
        protected MountedNamespaceDirectories $mountedNamespaceDirectories,
    ) {
    }

    /**
     * Determine if the current view is a Volt component.
     */
    protected function shouldExtractTemplate(string $template): bool
    {
        if (is_null($path = Blade::getPath())) { // @phpstan-ignore function.impossibleType
            return false;
        }

        return $this->mountedDirectories->isWithinMountedDirectory($path)
            || $this->mountedNamespaceDirectories->isWithinMountedDirectory($path);
    }
}

<?php

namespace Ganyicz\VoltNamespace;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Livewire\Volt\MountedDirectory;

class MountedNamespaceDirectories
{
    /**
     * The mounted directory paths.
     *
     * @var array<string, array<int, \Livewire\Volt\MountedDirectory>>
     */
    protected array $paths = [];

    /**
     * Mount the given path and auto-register its Volt components.
     *
     * @param  array<int, string>|string  $paths
     * @param  array<int, class-string>|class-string  $uses
     */
    public function mount(string $namespace, array|string $paths, array|string $uses = []): void
    {
        $paths = collect(Arr::wrap($paths))
            ->filter(fn (string $path) => is_dir($path))
            ->values()
            ->map(fn (string $path) => new MountedDirectory($path, Arr::wrap($uses)));

        $this->paths[$namespace] = array_merge($this->paths[$namespace] ?? [], $paths->all());

        View::replaceNamespace('volt-livewire:'.$namespace, collect($this->paths[$namespace])->pluck('path')->all());
    }

    /**
     * Determine if the given path is within a mounted directory.
     */
    public function isWithinMountedDirectory(string $path): bool
    {
        return collect($this->paths)->flatten()->pluck('path')->contains(fn (string $m) => str_starts_with($path, $m));
    }

    /**
     * Get the mounted directory paths.
     *
     * @return array<int, \Livewire\Volt\MountedDirectory>
     */
    public function paths(string $namespace): array
    {
        return $this->paths[$namespace] ?? [];
    }
}

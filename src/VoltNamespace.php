<?php

namespace Ganyicz\VoltNamespace;

class VoltNamespace
{
    public function mount(string $namespace, array|string $paths, array|string $uses = []): void
    {
        app(MountedNamespaceDirectories::class)->mount($namespace, $paths, $uses);
    }
}

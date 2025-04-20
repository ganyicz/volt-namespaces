<?php

namespace Ganyicz\VoltNamespace;

use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder as Finder;

class FileViewFinder extends Finder
{
    /**
     * Get the path to a template with a named path.
     *
     * @param  string  $name
     * @return string
     */
    #[\Override]
    protected function findNamespacedView($name)
    {
        [$namespace, $view] = $this->parseNamespaceSegments($name);

        if ($namespace === 'volt-livewire' && Str::contains($view, ':')) {
            $segments = explode(':', $view);

            $namespace = 'volt-livewire:' . $segments[0];
            $view = $segments[1];
        }

        return $this->findInPaths($view, $this->hints[$namespace]);
    }
}

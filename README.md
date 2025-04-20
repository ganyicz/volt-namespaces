# Volt Namespaces

This is a (working) proof of concept.

## Usage

Install the package via composer:

```
composer require ganyicz/volt-namespace
```

Mount a new namespace in your VoltServiceProvider:

```diff
public function boot(): void
{
    Volt::mount([
        config('livewire.view_path', resource_path('views/livewire')),
        resource_path('views/pages'),
    ]);

+   VoltNamespace::mount('admin', resource_path('views/admin/livewire'));
}
```

Use the namespace when rendering a volt component:

```html
<livewire:admin:search-users />
```

The above will load a volt component from the following path:

```
/resources/views/admin/livewire/search-users.blade.php
```

## Why

Using namespaces allows for a cleaner directory structure when maintaining multiple frontends in one repo. With Blade, we can already register a namespace for anonymous components like this:

```
Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');
```

Combined, this allows us to have the following structure that keeps all related views together, including livewire, blade components and regular views:

```
resources
└─ views
  └─ admin
    └─ auth
    └─ pages
    └─ livewire
    └─ components
```

Whereas _without_ namespaces, we would have to use subdirectories, which fragments the structure:

```
resources
└─ views
  └─ admin
    └─ auth
    └─ pages
  └─ components
    └─ admin
  └─ livewire
    └─ admin
```

## Disclaimer

This packge has only been tested in a single project with a specific setup and alters core functionalities of Laravel and Volt. This could have unintended consequences in your app. Use at your own risk.

If this functionality were to be added to the core of Volt, this could be achieved with a lot less hackery.

## How it works

1. It binds a custom view finder that extends the `Illuminate\View\FileViewFinder`. This is required to load the correct view in the namespaced components.

    Volt uses a custom `volt-livewire` view namespace that contains all mounted paths and when a component is rendered it will try to load the following view for `<livewire:search-users />`:

    ```
    volt-livewire::search-users
    ```

    The first part is the custom view namespace and the second part is the component name that Volt will look for in all mounted paths. In our case the name of the component would contain the "sub-namespace" like this:

    ```
    volt-livewire::admin:search-users
    ```

    The package simply moves the primary namespace separator like this:

    ```
    volt-livewire:admin::search-users
    ```

    Where `volt-livewire:admin` is a new view namespace that is registered by the pacakge when a volt namespace is mounted. This namespace contains the paths defined by the user in `VoltNamespace::mount()` method.

2. It replaces the `Livewire\Volt\Precompilers\ExtractTemplate` precompiler to include the registered namespaced directories in the pre-compilation process.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Filip Ganyicz](https://github.com/ganyicz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

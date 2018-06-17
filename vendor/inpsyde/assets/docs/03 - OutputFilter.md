# `OutputFilter`
These callbacks are specified to manipulate the output of the `Script` via `script_loader_tag` and `Style` via `style_loader_tag`.

To use an `OutputFilter` you've to assign them to a specific asset:

```php
<?php
use Inpsyde\Assets\AssetFactory;
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\OutputFilter\AsyncScriptOutputFilter;

$script = AssetFactory::create(
	[
		'handle' => 'my-handle',
		'src' => 'script.js',
		'location' => Asset::FRONTEND,
		'type' => Script::class,
		'filters' => [AsyncScriptOutputFilter::class]
	]
);
```

## Available filters
Following default OutputFilters are shipped with this package:

### `AsyncScriptOutputFilter`

This filter will add the `async`-attribute to your script-tag: `<script async src="{url}"><script>`

### `DeferScriptOutputFilter`

This filter will add the `defer`-attribute to your script tag: `<script defer src="{url}"><script>`

### `AsyncStyleOutputFilter`
This filter will allow you to load your CSS async via `preload`. It also delivers a polyfill for older browsers which is appended once to ensure that script-loading works properly.

```
<link rel="preload" href="{url}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{url}" /></noscript>
<script>/* polyfill for older browsers */</script>
```

## Create your own filter
You can either implement the `Inpsyde\Assets\OutputFilter\AssetOutputFilter`-interface or just use a normal callable function which will applied on the `Asset`:

```php
<?php
use Inpsyde\Assets\AssetFactory;
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\Script;

$customFilter = function( string $html, Asset $asset ): string
{
    // do whatever you have to do.

    return $html;
};

$script = AssetFactory::create(
	[
		'handle' => 'my-handle',
		'src' => 'script.js',
		'location' => Asset::FRONTEND,
		'type' => Script::class,
		'filters' => [$customFilter]
	]
);

```

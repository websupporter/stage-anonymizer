<?php declare(strict_types=1); # -*- coding: utf-8 -*-
/*
 * This file is part of the Assets package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Assets;

use Inpsyde\Assets\Handler\ScriptHandler;

class Script extends BaseAsset implements Asset
{

    public function __construct(
        string $handle,
        string $url,
        int $location = Asset::FRONTEND,
        array $config = []
    ) {

        $config['handle'] = $handle;
        $config['url'] = $url;
        $config['location'] = $location;

        $this->config = array_replace($this->config, $config);
    }

    public function localize(): array
    {
        $localize = $this->config['localize'] ?? [];
        is_callable($localize) and $localize = $localize();

        return (array) $localize;
    }

    public function inFooter(): bool
    {
        return (bool) ($this->config['inFooter'] ?? true);
    }

    public function handler(): string
    {
        return (string) ($this->config['handler'] ?? ScriptHandler::class);
    }
}

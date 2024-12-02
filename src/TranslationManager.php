<?php

namespace OpenAdmin\TranslationManager;

use OpenAdmin\Admin\Extension;

class TranslationManager extends Extension
{
    public $name = 'oa-translation-manager';

    public $views = __DIR__ . '/../resources/views';

    public $migrations = __DIR__ . '/../database/migrations';

    public $menu = [
        'title' => 'Translation manager',
        'path'  => 'translation-manager',
        'icon'  => 'icon-language',
    ];
}

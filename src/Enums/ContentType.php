<?php

declare(strict_types=1);

namespace Rimba\Versioning\Enums;

use Filament\Support\Contracts\HasLabel;

enum ContentType: string implements HasLabel
{
    case FilamentPage = 'filament-page';
    case FilamentResource = 'filament-resource';
    case Route = 'route';
    case Url = 'url';
    case Markdown = 'markdown';
    case Document = 'document';
    case Folder = 'folder';
    case Report = 'report';
    case Dashboard = 'dashboard';
    case Api = 'api';
    case File = 'file';
    case Video = 'video';
    case Html = 'html';

    public function getLabel(): ?string
    {
        return ucwords(str_replace('-', ' ', $this->value));
    }
}

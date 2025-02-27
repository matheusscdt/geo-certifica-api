<?php

namespace App\Enums;

enum RoutingKeyEnum: string
{
    case create = 'create';
    case update = 'update';
    case delete = 'delete';
}

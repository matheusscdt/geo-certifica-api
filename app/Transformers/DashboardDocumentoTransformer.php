<?php

namespace App\Transformers;

use App\Dto\DashboardDocumentoDto;

class DashboardDocumentoTransformer extends BaseTransformer
{
    public function transform(DashboardDocumentoDto $dashboardDocumentoDto): array
    {
        return [
            'id' => $dashboardDocumentoDto->id,
            'title' => $dashboardDocumentoDto->title,
            'iconBgColor' => $dashboardDocumentoDto->iconBgColor,
            'iconTextColor' => $dashboardDocumentoDto->iconTextColor,
            'count' => $dashboardDocumentoDto->count,
            'pastaId' => $dashboardDocumentoDto->pastaId,
        ];
    }
}

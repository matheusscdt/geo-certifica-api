<?php

namespace App\Dto;

use App\Enums\DashboardDocumentoPainelEnum;

class DashboardDocumentoDto
{
    public function __construct(DashboardDocumentoPainelEnum $dashboardDocumentoPainelEnum)
    {
        $this->dashboardDocumentoPainelEnum = $dashboardDocumentoPainelEnum;
        $this->title = $dashboardDocumentoPainelEnum->label();
        $this->iconBgColor = $dashboardDocumentoPainelEnum->iconBgColor();
        $this->iconTextColor = $dashboardDocumentoPainelEnum->iconTextColor();
    }

    public DashboardDocumentoPainelEnum $dashboardDocumentoPainelEnum {
        get {
            return $this->dashboardDocumentoPainelEnum;
        }
    }

    public int $id {
        get {
            return $this->dashboardDocumentoPainelEnum->value;
        }
    }

    public string $title {
        get {
            return $this->title;
        }
    }
    public string $iconBgColor {
        get {
            return $this->iconBgColor;
        }
    }
    public string $iconTextColor {
        get {
            return $this->iconTextColor;
        }
    }
    public int $count {
        get {
            return $this->count;
        }
        set {
            $this->count = $value;
        }
    }
    public ?int $pastaId {
        get {
            return $this->pastaId;
        }
        set {
            $this->pastaId = $value;
        }
    }
}

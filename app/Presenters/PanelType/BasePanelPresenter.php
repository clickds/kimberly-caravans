<?php

namespace App\Presenters\PanelType;

use App\Models\Area;
use App\Models\Panel;
use App\Models\Site;
use McCool\LaravelAutoPresenter\BasePresenter;
use App\Services\Site\HeadingClassesGenerator\BaseGenerator as HeadingClassGenerator;

class BasePanelPresenter extends BasePresenter
{
    private Site $site;

    public function areaColumns(): int
    {
        $area = $this->getPanel()->area;
        if ($area) {
            return $area->columns;
        }
        return 1;
    }

    public function partialPath(): string
    {
        return "site.panels.{$this->getType()}.main";
    }

    public function cssClasses(): string
    {
        $classes = [
            "relative",
            "panel",
            "panel--{$this->getType()}",
        ];

        $classes[] = $this->getPanel()->text_alignment;
        $classes[] = $this->gridAlignClassForVerticalPositioning();

        return implode(" ", $classes);
    }

    private function gridAlignClassForVerticalPositioning(): string
    {
        if ($this->getPanel()->hasTopPositioning()) {
            return "grid-self-start";
        }
        if ($this->getPanel()->hasMiddlePositioning()) {
            return "grid-self-center";
        }
        if ($this->getPanel()->hasBottomPositioning()) {
            return "grid-self-end";
        }
        return "grid-self-stretch";
    }

    public function displayCaravans(): bool
    {
        return in_array($this->getVehicleType(), [Panel::VEHICLE_TYPE_BOTH, Panel::VEHICLE_TYPE_CARAVAN]);
    }

    public function displayMotorhomes(): bool
    {
        return in_array($this->getVehicleType(), [Panel::VEHICLE_TYPE_BOTH, Panel::VEHICLE_TYPE_MOTORHOME]);
    }

    public function headingCssClasses(): string
    {
        $panel = $this->getPanel();
        $area = $panel->area;
        if (is_null($area)) {
            return "";
        }
        $headingType = $panel->heading_type;

        $headingClassGenerator = HeadingClassGenerator::buildGenerator($area->background_colour, $headingType);
        return $headingClassGenerator->call();
    }

    public function getContent(): ?string
    {
        return $this->getPanel()->content;
    }

    public function getHeading(): ?string
    {
        return $this->getPanel()->heading ?: "";
    }

    public function getHeadingType(): ?string
    {
        return $this->getPanel()->heading_type;
    }

    public function getType(): string
    {
        return $this->getPanel()->type;
    }

    public function getVehicleType(): ?string
    {
        return $this->getPanel()->vehicle_type;
    }

    protected function getPanel(): Panel
    {
        return $this->getWrappedObject();
    }

    protected function getSite(): Site
    {
        if (!isset($this->site)) {
            $this->site = resolve('currentSite');
        }
        return $this->site;
    }
}

<?php

namespace App\Services\Navigation;

use App\Models\NavigationItem;
use Illuminate\Database\Eloquent\Collection;
use UnexpectedValueException;

final class NavigationItemsHierarchyUpdater
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\NavigationItem>
     */
    private Collection $navigationItems;
    private array $hierarchyData;

    public function __construct(Collection $navigationItems, array $hierarchyData)
    {
        $this->navigationItems = $navigationItems;
        $this->hierarchyData = $hierarchyData;
    }

    public function update(): void
    {
        $this->clearDisplayOrdersAndParents();

        $displayOrder = 1;

        foreach ($this->hierarchyData as $hierarchyDatum) {
            $parent = $this->navigationItems->where('id', $hierarchyDatum['navigationItemId'])->first();

            if (is_null($parent)) {
                throw new UnexpectedValueException('Expected a navigation item to be found');
            }

            $parent->display_order = (int) $displayOrder;
            $parent->save();

            if (isset($hierarchyDatum['children']) && 1 <= count($hierarchyDatum['children'])) {
                $this->createChildrenForParent(
                    $parent,
                    $hierarchyDatum['children'],
                    $displayOrder
                );
            }

            $displayOrder++;
        }
    }

    private function clearDisplayOrdersAndParents(): void
    {
        $this->navigationItems->map(function (NavigationItem $navigationItem) {
            $navigationItem->update(['display_order' => 0, 'parent_id' => null]);
        });
    }

    private function createChildrenForParent(
        NavigationItem $parent,
        array $childrenHierarchyData,
        int &$displayOrder
    ): void {
        foreach ($childrenHierarchyData as $childrenHierarchyDatum) {
            $displayOrder++;

            $child = $this->navigationItems->where('id', $childrenHierarchyDatum['navigationItemId'])->first();

            if (is_null($child)) {
                throw new UnexpectedValueException('Expected a navigation item to be found');
            }

            $child->display_order = $displayOrder;
            $child->save();

            $parent->children()->save($child);
        }
    }
}

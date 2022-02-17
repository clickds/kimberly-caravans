<?php

namespace App\Repositories;

use App\Models\Category;

/**
 * Class CategoryRepository
 * @package App\Repositories
 */
class CategoryRepository
{


    /**
     * @param string $catName
     * @return Category
     */
    public function getByNameOrCreate(string $catName) : Category
    {


        /** @var Category $cat */
        if(!$cat = Category::where('name',$catName)->first()){

            // create new cat
            $cat = new Category;
            $cat->name = $catName;
            $cat->save();
        }

        return $cat;
    }
}

<?php

namespace App\Repositories;



use App\Models\Type;

/**
 * Class TypeRepository
 * @package App\Repositories
 */
class TypeRepository
{

    /**
     * @param string $typeName
     * @return Type
     */
    public function getByNameOrCreate(string $typeName) : Type
    {


        /** @var Type $type */
        if(!$type = Type::where('name',$typeName)->first()){

            // create new type
            $type = new Type;
            $type->name = $typeName;
            $type->save();
        }

        return $type;
    }
}

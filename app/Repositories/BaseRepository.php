<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
class BaseRepository
{


    /**
     * @var string|null
     */
    protected ?string $error;

    /**
     * @var string
     */
    protected string $modelName = '';

    /**
     * @var string
     */
    protected string $tableName = '';

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {

        // If modelName is empty, set it manually otherwise child class cqn override
        $matches = [];
        $className = get_class($this);
        if(!$this->modelName && preg_match('/([A-Z]{1}[a-z]+)Repository/',$className,$matches)){
            $this->modelName = "App\\Models\\" . $matches[1];
        }

        // same thing with table name
        if(!$this->tableName){
            $this->tableName = app($this->modelName)->getTable();
        }
    }

    /**
     * @return string|null
     */
    public function getError() : ?string
    {
        return $this->error;
    }

    /**
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return Collection|null
     */
    public function findAll(array $orderBy = [], int $limit = 20, int $offset = 0) : ?Collection
    {

        // load the table for the query
        $query = DB::table($this->tableName);

        if(!empty($orderBy)){
            foreach($orderBy as $orderCol => $order){
                $query->orderBy($orderCol,$order);
            }
        }

        $query->limit($limit);
        $query->offset($offset);


        return $query->get();
    }

    /**
     * @param array $where
     * @return Model|null
     */
    public function findOneBy(array $where) : ?Model
    {
        $query = DB::table($this->tableName);

        foreach($where as $col => $val){
            $query->where($col,$val);
        }

        $query->limit(1);
        return $query->first();
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return Collection|null
     */
    public function findBy(array $where, array $orderBy = [], int $limit = 20, int $offset = 0) : ?Collection
    {
        // load the table for the query
        $query = DB::table($this->tableName);

        foreach($where as $col => $val){
            $query->where($col,$val);
        }

        if(!empty($orderBy)){
            foreach($orderBy as $orderCol => $order){
                $query->orderBy($orderCol,$order);
            }
        }

        $query->limit($limit);
        $query->offset($offset);


        return $query->get();
    }

}
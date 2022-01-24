<?php

namespace App\Models;

use Illuminate\Support\Arr;
use JsonSerializable;

class Tab implements JsonSerializable
{
    private string $id;
    private string $title;

    public function __construct(array $attributes)
    {

        $this->id = Arr::get($attributes, 'id');
        $this->title = Arr::get($attributes, 'title');
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->title;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'   => $this->getId(),
            'title' => $this->getName()
        ];
    }
}

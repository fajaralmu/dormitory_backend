<?php

namespace App\Models;

class CategoryPredicate extends BaseModel
{
    protected $table = 'category_predicate';

    protected $fillable = [
        'name',
        'category_id',
        'description',
    ];

    protected $id;
    protected $name;
    protected $category_id;
    protected $description;

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}

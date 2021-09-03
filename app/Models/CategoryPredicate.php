<?php

namespace App\Models;

class CategoryPredicate extends BaseModel
{
    protected $table = 'category_predicate';

    protected $fillable = [
        'code',
        'category_id',
        'name',
        'description',
    ];

    protected $id;
    protected $code;
    protected $name;
    protected $category_id;
    protected $description;

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}

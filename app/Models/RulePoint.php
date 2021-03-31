<?php
namespace App\Models;

class RulePoint extends BaseModel
{

    protected $table = 'categories';

	protected $fillable = [
		'name',
        'description',
        'point',
	];

	protected $id;
	protected $name;
    protected $description;
    protected $point;
    public function category()
    {
        return $this->hasOne(Category::class, 'category_id');
    }
}
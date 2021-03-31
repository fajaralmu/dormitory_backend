<?php
namespace App\Models;

class RulePoint extends BaseModel
{

    protected $table = 'rule_points';

	protected $fillable = [
		'name',
        'description',
        'point',
        'category_id'
	];

	protected $id;
	protected $name;
    protected $description;
    protected $point;
    protected $category_id;
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
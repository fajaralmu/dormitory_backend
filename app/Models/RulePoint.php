<?php
namespace App\Models;

class RulePoint extends BaseModel
{

    protected $table = 'rule_points';

	protected $fillable = [
		'name',
        'description',
        'point',
        'category_id',
        'droppable'
	];

	protected $id;
	protected $name;
    protected $description;
    protected $point;
    protected $category_id;
    protected $droppable;
    
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
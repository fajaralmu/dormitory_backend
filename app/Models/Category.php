<?php

namespace App\Models;

use Mockery\Undefined;

class Category extends BaseModel
{

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $id;
    protected $name;
    protected $description;

    public function predicates()
    {
        return $this->hasMany(CategoryPredicate::class, 'category_id', 'id');
    }

    public function getPredicate($score) : CategoryPredicate
    {
        if (!isset($this->predicates)) {
            $this->load('predicates');
        }
        $letter = $this->scorePredicate($score);
        foreach ($this->predicates as $predicate) {
            if ($predicate->name == $letter) {
                return $predicate;
            }
        }
        return null;
    }

    private function scorePredicate($score) : string
    {
        $score = is_null($score) ? 0 : (int) $score;
        if (is_null($score) || $score < 74) {
            return "D";
        }
        if ($score < 80) {
            return "C";
        }
        if ($score < 90) {
            return "B";
        }
        return "A";
    }
}

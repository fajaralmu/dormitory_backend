<?php
//
namespace App\Models;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    
    public function getAttributes()
    {
        $attr = $this->attributes;
        if (sizeof($this->fillable) > 0) {
            foreach ($this->fillable as $fill) {
                $attr[$fill] = $this->$fill;
            }
        }
        // $this->jsonSerialize()
        // dd("attr: ", $attr);
        return $attr;
    }

    public function attributesToArray()
    {
        $parent_attr = parent::attributesToArray();
        $attr = ($this->getAttributes());
        foreach ($attr as $key => $value) {
            if (!Arr::get($parent_attr, $key)) {
                $parent_attr[$key] = $value;
            }
        }
        return $parent_attr;
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        parent::setRawAttributes($attributes, $sync);
        if (sizeof($this->fillable) > 0) {
            foreach ($this->fillable as $fill) {
                if (Arr::get($attributes, $fill)) {
                    $this->$fill = $attributes[$fill];
                }
            }
        }
    }
}

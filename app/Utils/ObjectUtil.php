<?php

namespace App\Utils;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

class ObjectUtil
{
    /**
     * generate query criteria by provided filter
     */
    public static function adjustFieldFilter(BaseModel $model, $filter = null)
    {
        if (is_null($filter)) {
            return null;
        }
        $fieldsFilter = $filter->fieldsFilter;
        $fillable = $model->getFillable();
        $filterable = $model->getFilterable();

        foreach ($fieldsFilter as $key => $value) {
            $exist = false;
            foreach ($filterable as $fillablekey) {
                if (!$exist && $key == $fillablekey) {
                    $exist = true;
                    break;
                }
            }
            foreach ($fillable as $fillablekey) {
                if (!$exist && $key == $fillablekey) {
                    $exist = true;
                    break;
                }
            }
            if ($exist == false) {
                unset($filter->fieldsFilter[$key]);
            } else {
                unset($filter->fieldsFilter[$key]);
                //if has select alias
                if (!is_null($model->getAlias($key))) {
                    $filter->fieldsFilter[$model->getAlias($key)] = $value;
                } else {
                    $filter->fieldsFilter[$model->getTable().'.'.$key] = $value;
                }
            }
        }

        return $filter;
    }
    public static function arraytoobj($obj, $arr)
    {
        $reflectionClass = new ReflectionClass($obj);
        
        foreach ($arr as $key => $value) {
            if ($reflectionClass->hasProperty($key) && !is_null($value)) {
                $prop = $reflectionClass->getProperty($key);

                $propName = ObjectUtil::getPropName($prop);
                $isCustomObject = substr($propName, 0, 4) === "App\\";

                if ($isCustomObject) {
                    out("==========>" . $propName);
                    // $propName = str_replace("Models\\Models", "Models", $propName);
                    $obj->$key = ObjectUtil::arraytoobj(new $propName(), $value);
                } else {
                    $obj->$key = $value;
                }
            }
        }

        return $obj;
    }

    public static function getPropName(ReflectionProperty $prop) {
        $propType = $prop->getType();
        $propName = $prop->name;
        if (!is_null($propType)) {
            $propName = $propType->getName(); //ReflectionNamedType::getName()
        }
        return $propName;
    }

    public static function customPluck($array, $valuePropName, $keyDelim, ...$keys)
    {
        $result = [];

        foreach ($array as $arr) {
            $theKey = [];
            foreach ($keys as $key) {
                if (is_null($arr[$key]) || $arr[$key] == "") {
                    continue;
                }
                array_push($theKey, $arr[$key]);
            }
            $result[join($keyDelim, $theKey)] = $arr[$valuePropName];
        }

        return $result;
    }

    public static function requestHas(Request $request, string $param) : bool
    {
        $result = $request->has($param) && !is_null($request->get($param));
         
        return $result;
    }

    public static function arrayToCollection(array $array) : Collection
    {
        $collection = new Collection($array);
        return $collection;
    }

    public static function highlightTextUpperCased($highlighted, string $string) : string
    {
        $filter = strtoupper($highlighted);
        $replacement = "<b>".$filter."</b>";
        $string = str_replace($filter, $replacement, strtoupper($string));
        return $string;
    }

    public static function mysqlEscape($inp)
    {
        if (is_array($inp)) {
            return array_map(__METHOD__, $inp);
        }

        if (!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
        }

        return $inp;
    }

    public static function allFalse(bool ...$expressions) : bool
    {
        for ($i=0; $i < sizeof($expressions); $i++) { 
            if ($expressions[$i] == true) {
                return false;
            }
        }
        return true;
    }

    public static function accessProtected($obj, $prop)
    {
        $reflection = new ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
      }

    public static function collectionToPlainArray(Collection $collection) : array
    {
        $array = [];

        foreach ($collection as $item) {
            array_push($array, $item);
        }
        return $array;
    }

}
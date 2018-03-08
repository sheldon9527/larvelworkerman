<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

class BaseTransformer extends TransformerAbstract
{
    // 默认的transform，返回对象的所有信息
    public function transform(Model $object)
    {
        return $object->attributesToArray();
    }
}

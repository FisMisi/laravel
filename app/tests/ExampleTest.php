<?php

class ExampleTest extends TestCase 
{

  public static function getCategories($type, $modelId)
    {   #DB::enableQueryLog();
        $query = self::leftJoin('model_model_category', function($join) use($modelId)
            {
            $join->on('model_model_category.category_id', '=', 'model_categories.id')
                ->where('model_model_category.model_id', '=', $modelId);
            })
                ->where('model_categories.type_id', '=', $type)
                ->where('model_categories.active', '=', 1);
        $ret = $query->get(array('model_categories.id','model_categories.title','model_model_category.model_id'))->toArray();     
        /*$queries = DB::getQueryLog();
        $last_query = end($queries);
        var_dump($last_query);*/
        return $ret;
    }
    
//    <h2>Your Categories</h2>    
//  @foreach($helperDataJson['categoryTypes'] as $type)
//    <div>
//        <b>{{ $type['title'] }}</b>
//        @foreach(ModelCategory::getCategories($type['id'],$helperDataJson['userModel']->id) as $category)
//            @if($type['multi'] == 1)
//               {{ Form::checkbox($type['name'].'[]',$category['id'],!is_null($category['model_id'])) }} {{ $category['title'] }}  
//            @else
//               {{ Form::radio($type['name'],$category['id'],!is_null($category['model_id'])) }} {{ $category['title'] }}
//            @endif 
//        @endforeach
//    </div>
//    @endforeach 
//  </div>
}

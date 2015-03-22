<?php $categoryTypes = CategoryType::getCategoryTypes() ?>
@if(count($categoryTypes))
    {{Form::open(array('route' => array('products.lists')))}}
    
        <div class="form-group">
            @foreach($categoryTypes as $type)
            <div class="col-sm-12">
                
                <h5><b>{{ $type['title'] }}</b></h5>

                @foreach(Category::getCategories($type['id'],null) as $category)
                    @if($type['multi'] == 1)
                       {{ Form::checkbox($type['id'].'[]',$category['categId'],!is_null($category['userId'])) }} {{ $category['categTitle'] }}<br />  
                    @else
                       {{ Form::radio($type['id'],$category['categId'],!is_null($category['userId'])) }} {{ $category['categTitle'] }} </br />
                    @endif 
                @endforeach

            </div>
            @endforeach 
        </div>
        
        <div class="form-group">
            <div class="col-sm-12">
                {{ Form::submit('Search',array('class'=>'btn btn-primary')) }}
            </div>
        </div>
    
    {{Form::close()}}

@endif

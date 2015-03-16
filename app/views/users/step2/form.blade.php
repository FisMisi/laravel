<h2 class="col-sm-offset-2">Your Categories</h2>

<div class="form-group">
@foreach($categoryTypes as $type)
<div class="col-sm-offset-2 col-sm-10">
    <b>{{ $type['title'] }}</b>
    
    @foreach(Category::getCategories($type['id'],$user->id) as $category)
      {{$category['userId']}}
        @if($type['multi'] == 1)
           {{ Form::checkbox($type['name'].'[]',$category['categId'],!is_null($category['userId'])) }} {{ $category['categTitle'] }}  
        @else
           {{ Form::radio($type['name'],$category['categId'],!is_null($category['userId'])) }} {{ $category['categTitle'] }}
        @endif 
    @endforeach
    
</div>
@endforeach 
</div>

<div class="form-group">  
    {{ Form::label('password','Jelszó', array('class' => 'col-sm-3 control-label')) }}
    <div class="col-sm-3">
        {{ Form::password('password', null, array('class' => 'form-control')) }}
        {{ $errors->first('password') }}
    </div>
</div>

<div class="form-group">  
    {{ Form::label('password_confirmation','Jelszó újra', array('class' => 'col-sm-3 control-label')) }}
    <div class="col-sm-3">
        {{ Form::password('password_confirmation', null, array('class' => 'form-control')) }}
        {{ $errors->first('password') }}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        {{ Form::hidden('user_id',$user->id) }}
        {{ Form::submit('Save',array('class'=>'btn btn-success')) }}
    </div>
</div>
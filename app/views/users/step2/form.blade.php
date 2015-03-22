<h1 class="col-sm-offset-2">Kínálat beállításai</h1>

 @if ($errors->has())
  <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
          {{ $error }}<br>        
      @endforeach
  </div>
@endif

<div class="form-group">
  <div class="col-sm-offset-2 col-sm-10">  
    <label class="radio-inline">
      {{Form::radio('os_v_vallalkozas',0)}} Östermelő
    </label>
    <label class="radio-inline">
      {{Form::radio('os_v_vallalkozas',1)}} Vállalkozás
    </label>
    {{ $errors->first('os_v_vallalkozas') }}
 </div>   
</div>

<div class="form-group ">  
    {{ Form::label('vallalkozas_nev','Vállalkozás neve', array('class' => 'col-sm-offset-2 col-sm-3 control-label')) }}
    <div class="col-sm-3">
        {{ Form::text('vallalkozas_nev', null, array('class' => 'form-control')) }}
        {{ $errors->first('vallalkozas_nev') }}
    </div>
</div>

<div class="form-group">
@foreach($categoryTypes as $type)
<div class="col-sm-offset-2 col-sm-10">
    <h3><b>{{ $type['title'] }}</b></h3>
    
    @foreach(Category::getCategories($type['id'],$user->id) as $category)
        @if($type['multi'] == 1)
           {{ Form::checkbox($type['id'].'[]',$category['categId'],!is_null($category['userId'])) }} {{ $category['categTitle'] }}  
        @else
           {{ Form::radio($type['id'],$category['categId'],!is_null($category['userId'])) }} {{ $category['categTitle'] }}
        @endif 
    @endforeach
    
</div>
@endforeach 
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        {{ Form::hidden('user_id',$user->id) }}
        {{ Form::submit('Save',array('class'=>'btn btn-success')) }}
    </div>
</div>
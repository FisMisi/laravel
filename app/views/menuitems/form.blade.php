
<div class="form-group">
    {{ Form::label('name', 'Menu elem neve', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('price', 'Ára', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::text('price', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('image', 'Kép', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::file('image') }}
    </div>
</div>


<div class="form-group">
  @foreach(Category::all() as $categ)  
    <div class="radio">
    {{ Form::label('category_id', $categ->name, array('class' => 'col-sm-3 control-label')) }}
    {{ Form::radio('category_id', $categ->id) }}
    </div>
  @endforeach
</div>
<div class="form-group">
    <div class='col-sm-offset-3 col-sm-4'>
        {{ Form::submit('mentés',array('class' => 'btn btn-success')) }}
    </div>
</div>
    

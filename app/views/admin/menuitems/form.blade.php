
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
        @if(!empty($menuitem->image))
          {{ HTML::image($menuitem->image, $menuitem->name, array('width' => '50')) }}
        @else
         <i> Nincs kép </i>
        @endif
        {{ Form::file('image') }}
    </div>
</div>

<div class="form-group">
    
  @foreach(Category::all() as $categ)
    <div class="col-sm-offset-3 col-sm-9">
        <div class="radio">
            <label>
               {{ Form::radio('category_id', $categ->id) }} {{ $categ->name }}
            <label>
        </div>
    </div>    
 @endforeach
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <div class="checkbox">
        <label>
          {{ Form::checkbox('availability') }} Availability
        </label>
      </div>
    </div>
</div>

@if(!empty($menuitem->id))
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <div class="checkbox">
        <label>
          {{ Form::checkbox('delete') }} Delete {{ $menuitem->name }}
        </label>
      </div>
    </div>
</div>
@endif

<div class="form-group">
    <div class='col-sm-offset-3 col-sm-4'>
        {{ Form::submit('mentés',array('class' => 'btn btn-success')) }}
    </div>
</div>
    

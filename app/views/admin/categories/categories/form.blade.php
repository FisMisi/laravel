<div class="form-group">
    {{ Form::label('name', 'Name', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::text('title', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('type_id', 'Category Type', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ $categoryType->title }}
        {{ Form::hidden('type_id', $categoryType->id) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('position', 'Position', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::text('position', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <div class="checkbox">
        <label>
          {{ Form::checkbox('active') }} Active
        </label>
      </div>
    </div>
</div>

<div class="form-group">
    <div class='col-sm-offset-3 col-sm-4'>
        {{ Form::submit('save',array('class' => 'btn btn-success')) }}
    </div>
</div>
<div class="form-group">
    {{ Form::label('name', 'Category Type Name', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('title', 'Category Type Title', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ Form::text('title', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('category_id', 'Category Name', array('class' => 'col-sm-3 control-label')) }}
    <div class='col-sm-4'>
        {{ $category->name }}
        {{ Form::hidden('category_id', $category->id) }}
    </div>
</div>

<div class="form-group">
    <div class='col-sm-offset-3 col-sm-4'>
        {{ Form::submit('Save',array('class' => 'btn btn-success')) }}
    </div>
</div>
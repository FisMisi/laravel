<h1> NEW LEVEL </h1>

{{ Form::open(array('route' => array('/administrator/model_levels/save'), 'class' => 'col-sm-10 form-horizontal'))}}
         @include('helper.admin.models.levels.form')
{{ Form::close() }}




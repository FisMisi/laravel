<h1> New Language </h1>

{{ Form::open(array('route' => array('/administrator/modelslanguages/save'), 'class' => 'col-sm-10 form-horizontal'))}}
         @include('helper.admin.models.languages.form');
{{ Form::close() }}




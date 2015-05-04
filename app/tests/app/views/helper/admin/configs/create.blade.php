<h1> NEW CONFIG </h1>

{{ Form::open(array('route' => array('/administrator/configs/save'), 'class' => 'col-sm-10 form-horizontal'))}}
         @include('helper.admin.configs.form')
{{ Form::close() }}




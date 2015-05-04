<h1> New Category Type </h1>

{{ Form::open(array('route' => array('/administrator/model_categories/save'), 'class' => 'col-sm-10 form-horizontal'))}}
         @include('helper.admin.modelcategories.categoryTypes.form');
{{ Form::close() }}




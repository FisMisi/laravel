<h1>Modify {{ $helperDataJson['categoryType']->title }} </h1>

{{ Form::model($helperDataJson['category'], array('route' => array('/administrator/model_categories/cat/update/{id}',$helperDataJson['category']->id), 'method'=>'put', 'class' => 'col-sm-10 form-horizontal')) }}
         @include('helper.admin.modelcategories.categories.form');
{{ Form::close() }}




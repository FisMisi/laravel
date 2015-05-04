<h1 class="col-sm-offset-1"><b> NEW VIDEO CATEGORY </b></h1>

{{ Form::open(array('route' => array('/administrator/video_storaged_categories/save'), 'class' => 'col-sm-10 form-horizontal'))}}
         @include('helper.admin.videostorage.categories.form')
{{ Form::close() }}




<div class="wrapper group" style="color:white">
    <h1>Welcome,</h1>
        update step2
        
     {{ Form::model($helperDataJson['userModel'], array('route' => array('/postmodelregistraton/step2/updateModelStep2', $helperDataJson['userModel']->id),'files'=>true,'method'=>'put')) }}
         @include('helper.modelregistration.step2.form')
     {{ Form::close() }}
     
      {{-- PARTIAL-PayPal teszt --}}
        @include('helper.partials.cart') 
</div>
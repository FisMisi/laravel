@extends('layouts.main')

@section('content')
<h1>Borok</h1>

    @if(count($borok))
        <ul>
        @foreach($borok as $bor)
            <li>
                <b>Név</b> - {{ $bor->megnevezes }}
                <input type="hidden" name="bor_id" id="bor_id" value="{{ $bor->id }}">
                <a href="#"  id="statusz" name="{{ $bor->id }}">
                       @if($bor->active_user == 1)
                           Inactivated
                       @else
                           Activated
                       @endif
                </a>
            </li>
        @endforeach
        </ul>
    @else
        <p>Nincs megjelenítendő étlap elem</p>
    @endif
    
    <script>
    
function setUserBorState(borId) {
    var datastring = 'borId='+borId;
    
    $.ajax({
            type: "POST",
            url: "/categories/public/borok/bor_statusz",
            data: datastring,
            success: function(data){
                if(data == 1){
                    $("#statusz").html("Inactivated");
                }else{
                    $("#statusz").html("Activated");
                } 				
            }
    }, 'json');
    
}    
    
$("document").ready(function()
{
	$("#statusz").click(function(event) {
            
//            event.preventDefault();
            
            var borId    = $("#bor_id").val();
            console.log(borId);
            setUserBorState(borId);
	});
        
});
</script>
    
@stop


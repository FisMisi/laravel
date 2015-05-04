@if($modelId)
    <div class="wrapper group">
        <div class="video-title-wrapper">
            <h1>My Gift Show Videos</h1>	
        </div>
        <div class="content-wrapper">
           @if(count(StoragedVideo::getModelVideos($modelId)))
              <ul>
              @foreach(StoragedVideo::getModelVideos($modelId) as $video)
                <li> <b>Name:</b>{{ $video->name }} - 
                     <b>Type:</b>{{ $video->type }} -
                     <b>Category Name:</b>{{ $video->categName }} - 
                     <b>Storage Reference:</b>{{ $video->storage_reference }} -
                     <b>Local store path:</b>{{ $video->local_store_path }}
                     
                     @if(!$video->active_admin)
                        <span title='{{ $video->inactivated_desctription }}'>
                            <b><i>This Video inactivated by Admin</i></b>
                        </span>
                     @endif
                     <input type="hidden" name="video_id" id="video_id" value="{{$video->id}}" />
                     <a name='statusz' id="statusz">
                     
                       @if($video->active_user == 1)
                           Inactivated
                       @else
                           Activated
                       @endif
                     </a>
                </li>
              @endforeach
              </ul>
           @endif
        </div>    
    </div>
@endif

<script>
    
function setModelVideoState(vid) {
    var datastring = 'videoid='+vid;
    
    $.ajax({
            type: "POST",
            url: "/postmodelregistraton/step2/video_statusz",
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
            
            var videoId    = $("#video_id").val();
            setModelVideoState(videoId);
	});
        
});
</script>
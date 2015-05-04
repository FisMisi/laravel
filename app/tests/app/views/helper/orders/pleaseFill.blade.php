<div class="wrapper group">
    <div class="row content-block-model">
            
    PLEASE BACK TO YOUR PROFIL AND FILL:
        <ul>
            @foreach($helperDataJson['errors'] as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    
    <a href="{{ route('/profil') }}"> Go To MY Profil </a>
    
    </div>
    
  </div>  
</div>
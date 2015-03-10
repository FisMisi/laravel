<!doctype html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <title>Administrator</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{ HTML::style('css/admin.css') }}
        
        <!-- Bootstrap -->
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="row">
            <div class="col-sm-9 col-sm-offset-1">
                <header>
                    <h1> ADMINISTRATOR</h1>
                </header>
                
               
                <div class="row">
                    <!-- oldalsó menü -->
                    @if(Auth::check())
                    
                    <div class="col-sm-3">
                        @include('layouts.partials.adminmenu')
                    </div>
                    
                    @endif
                    <!-- tartalom -->
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-12">
                                @yield('szuro')
                            </div>
                        </div>
                        <div>
                            @if (Session::has('message'))
                            <p class="bg-info flashmsg">{{Session::get('message')}}</p>
                            @endif
                        </div>
                        @yield('admincontent')    
                    </div>
                </div>     
            </div>   
        </div>  
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>    
    </body>
</html>
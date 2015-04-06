<!doctype html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <title>Kategóriás</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        {{ HTML::style('css/admin.css') }}
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
                    <h1> Kategóriás</h1>
                </header>

                <nav class="navbar navbar-default">
                    <div class="container-fluid">

                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">Kategóriás</a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                
                                <li {{{ (Request::is('menuitems*') ? 'class=active' : '') }}}><a href="{{URL::route('menuitems.index')}}">Menü</a></li>
                                <li {{{ (Request::is('menuitems/cart') ? 'class=active' : '') }}}><a href="{{URL::to('menuitems/cart')}}">Kosár</a></li>
                                <li {{{ (Request::is('products*') ? 'class=active' : '') }}}><a href="{{URL::route('products.lists')}}">Kínálat megtekintése</a></li>
                                @if(!Auth::check())
                                <li><a href="{{URL::route('users.create')}}">Regisztráció </a></li>
                                <li><a href="{{URL::route('login')}}">Belépés </a></li>
                                @else
                                <li><a href="#">Üdv. {{ Auth::user()->username }}</a></li>
                                <li><a href="{{URL::to('borok')}}">Kínálataim</a></li>
                                <li><a href="{{URL::to('users/edit')}}">Adataim módosítása</a></li>
                                <li><a href="{{URL::route('logout')}}">Kilépés </a></li>
                                @endif
                            </ul>

                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>  


                <div class="row">
                    <!-- oldalsó menü -->
                    <div class="col-sm-3">
                        {{ Form::open(array('route'=>array('menuitems.search'))) }}
                        {{Form::text('keyword',null,array('placeholder' => 'Ide írja be a keresendő tételt'))}}
                        {{Form::submit('Keresés')}}
                        {{ Form::close() }}
                        @yield('sidebar')
                    </div>
                    <!-- tartalom -->
                    <div class="col-sm-9">
                        <div>
                            @if (Session::has('message'))
                            <p class="bg-primary">{{Session::get('message')}}</p>
                            @endif
                            @if (Session::has('error'))
                            <p class="bg-primary">{{Session::get('error')}}</p>
                            @endif
                            @if (Session::has('success'))
                            <p class="bg-primary">{{Session::get('success')}}</p>
                            @endif
                        </div>
                        @yield('content')    
                    </div>
                </div>     
            </div>   
        </div>  
       <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    
    </body>
</html>
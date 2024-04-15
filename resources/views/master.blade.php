<!doctype html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>The Sweet Piece</title>
    <link rel="icon" href="https://getbootstrap.com/docs/4.0/assets/img/favicons/favicon.ico">


    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/blog/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- Custom styles -->
    <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/4.0/examples/blog/blog.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>

</head>

<body>

    <!-- Include navbar partial -->
    @include('partials.navbartwo')

    <!-- Yield contents section -->
    @yield('contents')

    <!-- Main content -->
    <main role="main" class="container">
        <div class="row">
            <div class="col-md-12 blog-main">
                <!-- Yield content section -->
                @yield('content')
            </div><!-- /.blog-main -->

        </div><!-- /.row -->

    </main><!-- /.container -->
</body>

     <!-- Footer -->
    <footer class="blog-footer">
        <p>
            <a href="#">Back to top</a>
        </p>
    </footer>


     <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/holder.min.js"></script>
    <script>
        Holder.addTheme('thumb', {
            bg: '#55595c',
            fg: '#eceeef',
            text: 'Thumbnail'
        });
    </script>

</body>
</html>

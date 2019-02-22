<!DOCTYPE html>
<html>
<head>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reddnot | Post</title>

    <!-- CSS -->
    <!-- <link rel="stylesheet" type="text/css" media="screen" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" media="screen" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/slate/bootstrap.min.css">

</head>
<body>

    <div id="app">

        <!-- Navigation -->
        <nav class="navbar navbar-expand-md navbar-dark text-light">
            <a class="navbar-brand" href="/">[ REDDNOT ]</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul v-show="!logged_in" class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="register" class="nav-link">&#9991; Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">&#9682; Sign In</a>
                    </li>
                </ul>
                <ul v-show="logged_in" class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="create" class="nav-link">&#9998; New Post</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">&#9683; Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            
        </div>

    </div>

<!-- Javascript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
    const app = new Vue({
        el: "#app",
        computed: {
            logged_in() {
                return localStorage.getItem('key') || false;
            }
        }
    });
</script>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reddnot | Register</title>

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
                    <li class="nav-item active">
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

        <div class="container mt-4">
            <div class="w-50 m-auto">
                <h2>Registration Form</h2>
                <hr>

                <div class="alert alert-danger d-none" id="errors">
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text">
                                &#9924;
                            </span>
                        </span>
                        <input class="form-control" id="name" type="text" value="" placeholder="my name" maxlength="128">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text">
                                @
                            </span>
                        </span>
                        <input class="form-control" id="email" type="email" value="" placeholder="user@email.com" maxlength="128">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text">
                                **
                            </span>
                        </span>
                        <input class="form-control" id="password" type="password" value="" placeholder="password" maxlength="18">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text">
                                **
                            </span>
                        </span>
                        <input class="form-control" id="repeat" type="password" value="" placeholder="repeat password" maxlength="18">
                    </div>
                </div>
                <div class="form-group text-right">
                    <a class="btn btn-outline-danger" href="/">Cancel</a>
                    <button class="btn btn-success" id="register" @click="register">Register</button>
                </div>
            </div>
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
        data: {
            client_id: 1,
            client_secret: "JUiWCKR4RgdHLZBBV99SdBFzSX6H7FSX5zlDnDii"
        },
        computed: {
            logged_in() {
                return localStorage.getItem('key') || false;
            }
        },
        methods: {
            register() {
                var name = $("#name").val();
                var email = $("#email").val();
                var password = $("#password").val();
                var repeat = $("#repeat").val();

                // Validate and register
                if (this.validateForm(name, email, password, repeat)) {
                    $.ajax({
                        url: "/api/register",
                        method: "post",
                        dataType: "json",
                        data: {
                            client_id: 1,
                            name: name,
                            email: email,
                            password: password,
                            repeat: repeat
                        },
                        statusCode: {
                            401: function(response) {
                                $("#errors").removeClass("d-none").html("");
                                for (var name in response.errors) {
                                    $("#" + name).addClass("is-invalid")
                                    $("#errors").html( response.errors[name].join("<br"));
                                }
                            }
                        }
                    });
                }
            },
            validateForm(name, email, password, repeat) {

                // Clear the errors alert
                $("#errors").addClass("d-none").html("");

                // Name length has parameters
                if (name.length < 3 || name.length > 128) {
                    $("#name").addClass("is-invalid");
                    $("#errors").removeClass("d-none").append("The name must be between 3 and 128 characters<br>");
                } else {
                    $("#name").removeClass("is-invalid");
                }

                // Email length has parameters
                if (email.length < 5 || email.length > 128) {
                    $("#email").addClass("is-invalid");
                    $("#errors").removeClass("d-none").append("Enter a valid email address please<br>");
                } else {
                    $("#email").removeClass("is-invalid");
                }

                // Passwords must match
                if (password != repeat) {
                    $("#password").addClass("is-invalid");
                    $("#repeat").addClass("is-invalid");
                    $("#errors").removeClass("d-none").append("The passwords do not match");

                } else {

                    // Email length has parameters
                    if (password.length < 8) {
                        $("#password").addClass("is-invalid");
                        $("#errors").removeClass("d-none").append("Minimum length of a password is 8 characters<br>");
                    } else {
                        $("#password").removeClass("is-invalid");
                        $("#repeat").removeClass("is-invalid");
                    }
                }

                return $("#errors").hasClass("d-none");
            }
        }
    });
</script>

</body>
</html>

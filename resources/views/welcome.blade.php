<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <title>Fake News Detection</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('fnd/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            text-shadow: 0 .05rem .1rem rgba(0, 0, 0, 0.9);
            box-shadow: inset 0 0 5rem rgba(0, 0, 0, 0.1);
            font-family: cursive;
            font-style: italic;
        }

        .nav-masthead .nav-link {
            color: rgba(255, 255, 255, .5);
            border-bottom: .25rem solid transparent;
        }

        .nav-masthead .nav-link:hover,
        .nav-masthead .nav-link:focus {
            border-bottom-color: rgba(255, 255, 255, .25);
        }

        .nav-masthead .nav-link + .nav-link {
            margin-left: 1rem;
        }

        .nav-masthead .active {
            color: #fff;
            border-bottom-color: #fff;
        }

        .formError {
            color: Red;
        }

    </style>
</head>

<body class="d-flex h-100 text-center text-bg-dark">
<div class="container d-flex w-100 mx-auto h-100 p-3 flex-column">
    <header class="mx-5">
        <div>
            <h3 class="mb-0"><img src="{{ asset('fnd/logo.png') }}" height="50" width="50"> Fake News Detection
            </h3>
            <nav class="nav nav-masthead justify-content-center mt-2">
                <a class="nav-link fw-bold py-1 px-0 active" aria-current="page" href="#">Home</a>
                <a class="nav-link fw-bold py-1 px-0" href="#">Methodology</a>
                <a class="nav-link fw-bold py-1 px-0" href="#">About Us</a>
                <a class="nav-link fw-bold py-1 px-0" href="#">Contact</a>
            </nav>
        </div>
    </header>
    <div class="m-5">
        <main class="px-3">
            <div>
                <nav class="nav nav-masthead">
                    <button id="text-link" class="btn btn-link nav-link fw-bold py-1 px-0 active"
                       onclick="myFunction('text-test')">Author, Title and Text</button>
                    <button id="url-link" class="btn btn-link nav-link fw-bold py-1 px-0"
                       onclick="myFunction('url-test')">URL</button>
                </nav>
            </div>
            <div id="text-test">
                <div class="mt-5 text-left">
                    <form id="textForm" method="post" action="{{ url('/text-test') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <h4>Title</h4>
                                <input name="title" type="text" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="form-group col-md-3">
                                <h4>Author</h4>
                                <input name="author" type="text" class="form-control" placeholder="Author" required>
                            </div>
                            <div class="form-group col-md-3">
                                <h4>Source</h4>
                                <input name="source" type="text" class="form-control" placeholder="Source" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <h4>News Text</h4>
                                <textarea name="text" class="form-control" rows="3"
                                          placeholder="News Text ..." required></textarea>
                            </div>
                        </div>
                        <div class="text-right">
                            <button id="textCheckButton" type="button" class="btn btn-primary">Check News</button>
                            <span id="textTimer" style="display: none;">Timer: <span class="Time">0</span>s</span>
                        </div>
                    </form>
                </div>
                <div class="mt-2 text-left formError">
                    <span id="formError" style="display: none;"></span>
                </div>
                <div id="textResult" class="mt-2 text-left" style="display: none;">
                    <h5>Result</h5>
                    <div id="resultContent"></div>
                </div>
            </div>
            <div id="url-test" style="display: none;">
                <div class="mt-5 text-left">
                    <form id="urlForm" method="post" action="{{ url('/url-test') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h4>URL</h4>
                            <input type="text" class="form-control" placeholder="Url" required>
                        </div>
                        <div class="text-right">
                            <button id="urlCheckButton" type="button" class="btn btn-primary">Check News URL</button>
                            <span id="urlTimer" style="display: none;">Timer: <span class="Time">0</span>s</span>
                        </div>
                    </form>
                </div>
                <div class="mt-2 text-left formError">
                    <span id="formError" style="display: none;"></span>
                </div>
                <div id="urlResult" class="mt-2 text-left" style="display: none;">
                    <h5>Result</h5>
                    <div id="urlResultContent"></div>
                </div>
            </div>
        </main>
    </div>
    <footer class="text-white-50 mt-auto">
        <p>Copyright © 2022-2023</p>
    </footer>
</div>
</body>
<script>
    function myFunction(id) {
        var x = document.getElementById(id);
        if (id === "text-test") {
            if (x.style.display === "none") {
                x.style.display = "block";
            }
            $(".formError").text("");
            document.getElementById('text-link').classList.add('active')
            document.getElementById('url-test').style.display = "none"
            document.getElementById('url-link').classList.remove('active')
        } else if (id === "url-test") {
            if (x.style.display === "none") {
                x.style.display = "block";
            }
            $(".formError").text("");
            document.getElementById('url-link').classList.add('active')
            document.getElementById('text-test').style.display = "none"
            document.getElementById('text-link').classList.remove('active')
        }
    }

    var timerInterval;

    $(document).ready(function () {
        $("#textCheckButton").click(function () {
            var form = document.getElementById("textForm");
            var formData = new FormData(form);
            if (isFormValid(form)) {
                $("#textCheckButton").prop("disabled", true);
                $("#urlTimer").show();
                startTimer();
                $.ajax({
                    url: form.action,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        stopTimer();
                        $("#textCheckButton").prop("disabled", false);
                        $("#textResult").show();
                        $("#resultContent").html(response);
                    },
                    error: function (error) {
                        stopTimer();
                        $("#textCheckButton").prop("disabled", false);
                        $("#textResult").show();
                        $("#resultContent").html("Error occurred during the AJAX request.");
                    }
                });
                $(".formError").text("");
            } else {
                $(".formError").text("Please Fill All Required Inputs");
            }
        });

        $("#urlCheckButton").click(function () {
            var form = document.getElementById("urlForm");
            var formData = new FormData(form);
            if (isFormValid(form)) {
                $("#urlCheckButton").prop("disabled", true);
                $("#urlTimer").show();
                startTimer();
                $.ajax({
                    url: form.action,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        stopTimer();
                        $("#urlCheckButton").prop("disabled", false);
                        $("#urlResult").show();
                        $("#urlResultContent").html(response);
                    },
                    error: function (error) {
                        stopTimer();
                        $("#urlCheckButton").prop("disabled", false);
                        $("#urlResult").show();
                        $("#urlResultContent").html("Error occurred during the AJAX request.");
                    }
                });
                $(".formError").text("");
            } else {
                $(".formError").text("Please Fill All Required Inputs");
            }
        });
    });

    function isFormValid(form) {
        var inputs = form.querySelectorAll("input, textarea");
        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            if (input.hasAttribute("required") && input.value.trim() === "") {
                return false;
            }
        }
        return true;
    }

    function startTimer() {
        var time = 0;
        timerInterval = setInterval(function () {
            time++;
            $(".Time").text(time);
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
    }
</script>
</html>

<!doctype html>
<html dir="rtl" lang="ar" class="h-100">

<head>
    <meta charset="utf-8">
    <title>كاشف الأخبار الكاذبة</title>
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
            <h3 class="mb-0"><img src="{{ asset('fnd/logo.png') }}" height="50" width="50"> كاشف الأخبار الكاذبة
            </h3>
            <nav class="nav nav-masthead justify-content-center mt-2">
                <a class="nav-link fw-bold py-1 px-1 active" aria-current="page" href="#"> الصفحة الرئيسة </a>
                <a class="nav-link fw-bold py-1 px-1" href="#"> طريقة العمل </a>
                <a class="nav-link fw-bold py-1 px-1" href="#"> من نحن؟ </a>
                <a class="nav-link fw-bold py-1 px-1" href="#"> تواصل معنا </a>
            </nav>
        </div>
    </header>
    <div class="m-5">
        <main class="px-3">
            <div class="px-3">
                <nav class="nav nav-masthead">
                    <button id="text-link" class="btn btn-link nav-link fw-bold py-1 px-1 active"
                       onclick="myFunction('text-test')"> الكاتب ، المصدر ، العنوان والنص </button>
                    <button id="url-link" class="btn btn-link nav-link fw-bold py-1 px-1"
                       onclick="myFunction('url-test')"> العنوان الإلكتروني </button>
                </nav>
            </div>
            <div id="text-test">
                <div class="mt-5 text-right">
                    <form id="textForm" method="post" action="{{ url('/text-test') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <h4>العنوان</h4>
                                <input name="title" type="text" class="form-control" placeholder="عنوان المقالة" required>
                            </div>
                            <div class="form-group col-md-3">
                                <h4>الكاتب</h4>
                                <input name="author" type="text" class="form-control" placeholder="الكاتب" required>
                            </div>
                            <div class="form-group col-md-3">
                                <h4>المصدر</h4>
                                <input name="source" type="text" class="form-control" placeholder="المصدر" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <h4>نص المقالة</h4>
                                <textarea name="text" class="form-control" rows="3"
                                          placeholder="نص المقالة ..." required></textarea>
                            </div>
                        </div>
                        <div class="text-left">
                            <button id="textCheckButton" type="button" class="btn btn-primary">فحص الخبر</button>
                        </div>
                        <div class="text-left">
                            <span id="textTimer" style="display: none;">الوقت: <span class="Time">0</span>s</span>
                        </div>
                    </form>
                </div>
                <div class="mt-2 text-right formError">
                    <span id="formError" style="display: none;"></span>
                </div>
                <div id="textResult" class="mt-2 text-left" style="display: none;">
                    <h5>Result</h5>
                    <div id="resultContent"></div>
                </div>
            </div>
            <div id="url-test" style="display: none;">
                <div class="mt-5 text-right">
                    <form id="urlForm" method="post" action="{{ url('/url-test') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h4>العنوان الإلكتروني</h4>
                            <input name="url" type="text" class="form-control" placeholder="Url , العنوان الإلكتروني" required>
                        </div>
                        <div class="text-left">
                            <button id="urlCheckButton" type="button" class="btn btn-primary">فحص العنوان الإلكتروني</button>
                        </div>
                        <div class="text-left">
                            <span id="urlTimer" style="display: none;">الوقت: <span class="Time">0</span>s</span>
                        </div>
                    </form>
                </div>
                <div class="mt-2 text-right formError">
                    <span id="formError" style="display: none;"></span>
                </div>
                <div id="urlResult" class="mt-2 text-right" style="display: none;">
                    <h5>النتيجة</h5>
                    <div id="urlResultContent"></div>
                </div>
            </div>
        </main>
    </div>
    @include('footer')
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
                $("#textTimer").show();
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
                $(".formError").text("عذرًا ، تأكد من تعبئة الحقول المطلوبة أولا");
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
                $(".formError").text("عذرًا ، تأكد من تعبئة الحقول المطلوبة أولا");
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
    document.getElementById("textForm").addEventListener("keypress", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
      }
    });
    document.getElementById("urlForm").addEventListener("keypress", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
      }
    });
</script>
</html>

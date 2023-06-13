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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/cover/">
    <link href="https://getbootstrap.com/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <meta name="theme-color" content="#712cf9">

<style>

    body {
        text-shadow: 0 .05rem .1rem rgba(0, 0, 0, 0.9);
        box-shadow: inset 0 0 5rem rgba(0, 0, 0, 0.1);
        font-family: cursive; font-style: italic;
    }

    .cover-container {
        max-width: 42em;
    }

    /*
     * Header
     */

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

</style>
</head>

<body class="d-flex h-100 text-center text-bg-dark">
<div class="container d-flex w-100 mx-auto h-100 p-3 flex-column">
    <header class="mx-5">
        <div>
            <h3 class="mb-0"><img src="{{ asset('fnd/logo.png') }}" height="50" width="50" > Fake News Detection
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
                    <a id="text-link" class="nav-link fw-bold py-1 px-0 active" href="#" onclick="myFunction('text-test')">Author, Title and Text</a>
                    <a id="url-link" class="nav-link fw-bold py-1 px-0" href="#" onclick="myFunction('url-test')">URL</a>
                </nav>
            </div>
            <div id="text-test">
                <div class="mt-5 text-left">
                    <form method="post" action="{{ url('/text-test') }}">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <h4>Title</h4>
                                <input name="title" type="text" class="form-control" placeholder="Title">
                            </div>
                            <div class="form-group col-md-3">
                                <h4>Author</h4>
                                <input name="author" type="text" class="form-control" placeholder="Author">
                            </div>
                            <div class="form-group col-md-3">
                                <h4>Source</h4>
                                <input name="source" type="text" class="form-control" placeholder="Source">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <h4>News Text</h4>
                                <textarea name="text" class="form-control" rows="3" placeholder="News Text ..."></textarea>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Check News</button>
                        </div>
                    </form>
                </div>
                <div class="mt-2 text-left">
                    <h5>Result</h5>
                    <h6><a href="#">https://www.google.com</a> our probability 96%</h6>
                    <h6><a href="#">https://www.youtube.com</a> our probability 82%</h6>
                    <h6><a href="#">https://www.aljazeera.com</a> our probability 68%</h6>
                </div>
            </div>
            <div id="url-test" style="display: none;">
                <div class="mt-5 text-left">
                    <form method="post" action="{{ url('/url-test') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h4>URL</h4>
                            <input type="text" class="form-control" placeholder="Url">
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Check News URL</button>
                        </div>
                    </form>
                </div>
                <div class="mt-2 text-left">
                    <h5>Result</h5>
                    <h6><a href="#">https://www.google.com</a> our probability 96%</h6>
                    <h6><a href="#">https://www.youtube.com</a> our probability 82%</h6>
                    <h6><a href="#">https://www.aljazeera.com</a> our probability 68%</h6>
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
        if (id === "text-test"){
            if (x.style.display === "none") {
                x.style.display = "block";
            }
            document.getElementById('text-link').classList.add('active')
            document.getElementById('url-test').style.display = "none"
            document.getElementById('url-link').classList.remove('active')
        }else if(id === "url-test"){
            if (x.style.display === "none") {
                x.style.display = "block";
            }
            document.getElementById('url-link').classList.add('active')
            document.getElementById('text-test').style.display = "none"
            document.getElementById('text-link').classList.remove('active')
        }
    }
</script>
</html>

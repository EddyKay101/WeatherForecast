<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Weather</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/weather-icons.min.css">
        <style>
            .box {
                width:600px;
                margin-left:70%;
                border:1px solid #ccc;
            }
            .collapsible {
            background-color: #777;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            }

            .active, .collapsible:hover {
            background-color: #555;
            }

            .content {
            padding: 0 18px;
            display: none;
            overflow: hidden;
            background-color: #f1f1f1;
            }
        </style>
    </head>
    <body>
        <div class="jumbotron">
            <h3>Your Weather Forecast</h3>
            <div class="container box">
                @if(isset(Auth::user()->email))
                    <div class="alert aler-danger success-block">
                        <strong>Welcome {{ Auth::user()->email }}</strong>
                        <br>
                        <a href="{{ url('/main/logout') }}">Logout</a>
                    </div>
                @endif
        </div>
        </div>



        <main class="container text-center">
            <h1 class="display-1">Forecast</h1>

            <form action="{{ url('/main/weather')}}" class="form-inline" method="POST">
                {{ csrf_field() }}
                <div class="form-group mx-auto my-5">
                    <label class="sr-only" for="location">Enter Location</label>
                    <input type="text" class="form-control" id="location" placeholder="Location" name="location">

                    <button type="submit" class="btn btn-dark">Search</button>
                </div>
            </form>
            @if(isset($_POST['location']))
            <h2 class="display-4">Results for {{$location}}</h2>
            <div class="card p-4" style="margin:0 auto; max-width:320px;">
                <h2>Current Forecast</h2>
                <h3 class="display-2">{{round(celcius($data['currently']['temperature']))}}&deg;C</h3>
                <p class="lead">Wind Speed: {{round($data['currently']['windSpeed'])}}<abbr title="miles per hour">mph</p>
            </div>
            <div>
            </div>
            <ul class="list-group" style="margin:0 auto; max-width:320px;">
                <button type="button" class="collapsible">Hourly Forecast</button>
                <div class="content">
                @foreach($data['hourly']['data'] as $hour)
                    <li class="list-group-item d-flex justify-content-between">
                        <p class="lead m-0">
                            {{date("g.a", $hour['time'])}}
                        </p>
                        <p class="lead m-0">
                            {{round(celcius($hour['temperature']))}}&deg;C
                        </p>
                        <p class="lead m-0">
                            <span class="sr-only">Humidity</span>{{$hour['humidity']*100}}%
                        </p>
                    </li>
                    <p hidden>{{$count++}}</p>

                    @if($count==12)
                        @break
                    @endif
                @endforeach
                </div>
            </ul>
            <div class="row" >

                @foreach($data['daily']['data'] as $day)
                    <div class="col-12 col-md-3">
                        <div class="card p-4 mb-4">
                            <h2 class="h4">
                                {{date("l", $day['time'])}}
                            </h2>
                            <h3 class="display-4">
                                {{round((round(celcius($day['temperatureHigh']))+round(celcius($day['temperatureLow'])))/2)}}&deg;C
                            </h3>
                            <div class="d-flex justify-content-between">
                                <p class="lead">
                                    Hi: {{round(celcius($day['temperatureHigh']))}}&deg;C
                                </p>
                                <p class="lead">
                                    Lo: {{round(celcius($day['temperatureLow']))}}&deg;C
                                </p>
                            </div>
                            <p class="lead m-0">
                                Humidity: {{$day['humidity']*100}}%
                            </p>
                        </div>
                    </div>

                @endforeach
                </div>
            @endif
        </main>
    </body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        let coll = document.getElementsByClassName("collapsible");
        let i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            let content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
            });
        }
    </script>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>User Login</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="app.css" rel="stylesheet" />
    <style>
        .error {
            color: red !important
        }
    </style>
</head>

<body>


<div class="container">

    <div class="row">
            <!-- Show any success message -->
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! \Session::get('success') !!}</li>
                    </ul>
                </div>
            @endif
        <!-- Show any success message -->

            <!-- Check user is logged in -->
            @if(\Auth::check())
                <table cellspacing="0" cellpadding="0" class="user-table">
                    <tr id="user-table-top" >
                        <th colspan="2"><h3>Dashboard</h3></th>
                    </tr>
                    <tr>
                        <th>User Name</th>
                        <th>{{\Auth::user()->name}}</th>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <th>{{\Auth::user()->email}}</th>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <th>{{\Auth::user()->phone}}</th>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <th>{{\Auth::user()->age()}}</th>
                    </tr>
                    <tr>
                        <th>City</th>
                        <th>{{\Auth::user()->address->city}}</th>
                    </tr>
                    <tr>
                        <th>Post Code</th>
                        <th>{{\Auth::user()->address->post_code}}</th>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>{{\Auth::user()->address->address}}</th>
                    </tr>
                    <tr>
                        <th>Registration Date</th>
                        <th>{{\Auth::user()->registration_date}}</th>
                    </tr>
                    <tr>
                        <th><a href="{{url('logout')}}"> Logout</a></th>
                        <th><a href="{{url('register')}}">Update Information</a> </th>
                    </tr>

                </table>
            @else
                <div class='dash '>
                    <div class='error'> You are not logged in  </div>
                    <div>  <a href="{{url('login')}}">Login</a> | <a href="{{url('register')}}">Register</a> </div>
                </div>

        @endif
        <!-- Check user is logged in -->
    </div>
</div>

</body>
</html>

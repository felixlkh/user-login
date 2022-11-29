<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration </title>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
          rel="stylesheet"/>
    <link href="app.css" rel="stylesheet"/>
</head>
<body class="antialiased">
<div class=" container">
    <div id="pageMessages">
    </div>
    <div class="register">
        <div class="register-screen">
            @if(\Auth::check())
                <h1>Update </h1>
            @else
                <h1>Registration</h1>
            @endif

            <form method="post" id="handleRegisterAjax"
                  action="{{\Auth::check() ? url('do-update') : url('do-register') }}" name="postform">
                <div class="form-group">
                    <input type="text" placeholder="User Name" name="name"
                           value="{{\Auth::check() ? \Auth::user()->name : old('name') }}" class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Email" name="email"
                           value="{{\Auth::check() ? \Auth::user()->email : old('email') }}" class="form-control"/>
                    @csrf
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Phone (+Country Code) (Number)" name="phone"
                           value="{{\Auth::check() ? \Auth::user()->phone : old('phone') }}" class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Date Of Birth" name="dob"
                           value="{{\Auth::check() ? \Auth::user()->dob : old('dob') }}"
                           class="datepicker form-control"/>
                </div>

                <div class="form-group">
                    <input type="text" placeholder="City" name="city"
                           value="{{\Auth::check() ? \Auth::user()->address->city : old('city') }}"
                           class="form-control"/>
                </div>

                <div class="form-group">
                    <input type="text" placeholder="Post Code" name="post_code"
                           value="{{\Auth::check() ? \Auth::user()->address->post_code : old('post_code') }}"
                           class="form-control"/>
                </div>

                <div class="form-group">
                    <input type="text" placeholder="Address" name="address"
                           value="{{\Auth::check() ? \Auth::user()->address->address : old('address') }}"
                           class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" class="form-control"/>
                    {{\Auth::check() ? '(Leave blank to keep current password)' : ''}}
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Confirm Password" name="confirm_password" class="form-control"/>
                </div>
                <div class="form-group button">
                    <button type="submit" class="btn">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    function createAlert(title, summary, appendToId) {
        var alertClasses = ["alert", "animated", "flipInX"];
        var msg = $("<div />", {"class": alertClasses.join(" ")});
        $("<h4 />", {html: title}).appendTo(msg);
        $("<strong />", {html: summary}).appendTo(msg);
        $('#' + appendToId).prepend(msg);
    }


    $(function () {

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '-18y'
        });

        $(document).on("submit", "#handleRegisterAjax", function () {
            var e = this;

            $(this).find("[type='submit']").html("REGISTER...");
            $.post($(this).attr('action'), $(this).serialize(), function (data) {

                $('.register').hide();
                $(e).find("[type='submit']").html("REGISTER");
                if (data.status) {
                    createAlert('Success', data.msg, 'pageMessages')
                    setTimeout(function () {
                        window.location = data.redirect_location;
                    }, 3000);
                }


            }).fail(function (response) {

                $(e).find("[type='submit']").html("LOGIN");
                $(".error").remove();
                var erroJson = JSON.parse(response.responseText);
                for (var err in erroJson) {
                    for (var errstr of erroJson[err])
                        $("[name='" + err + "']").after("<div class='error'> * " + errstr + "</div>");
                }

            });
            return false;

        });

    });
</script>
</body>

</html>

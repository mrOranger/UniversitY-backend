<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UniversitY</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>
    <body class="antialiased" style = "padding: 5px;">
        <h3>
            Dear {{$user->first_name}}, {{$user->last_name}}
        </h3>

        <br/>
        <p>
            we are glad to inform you that your request to be registered in the UniverstY platform,
            has been completed successfully. The next step is to put the following code in the last's
            step form.
        </p>
        <p style="text-align: center;">{{ $uuid }}.</p>
        <p>
            Best Regards,
        </p>
        <p>
            the team of UniverstY.
        </p>
    </body>
</html>

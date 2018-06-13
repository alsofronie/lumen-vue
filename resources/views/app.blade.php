<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}"/>
    <title>{{ env('APP_NAME') }}</title>
</head>
<body>
    <div id="app">
        <system-messages></system-messages>
        <router-view :key="$route.fullPath"></router-view>
    </div>
    <script src="{{ mix('/js/manifest.js') }}"></script>
    <script src="{{ mix('/js/vendor.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>

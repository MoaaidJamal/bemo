<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bemo</title>
    @vite('resources/css/app.scss')
</head>
<body>
<div id="app"></div>
<div id="modals"></div>
@vite('resources/js/app.js')
<script>
    window.laravel = {
        url: '{{url('/')}}',
        api_url: '{{url('api')}}',
        access_token: '{{request('access_token')}}',
        date: '{{request('date')}}',
        status: '{{request('status')}}',
    }
</script>
</body>
</html>

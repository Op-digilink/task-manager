<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task Management</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    
</head>
<body>
   
    <div id="app"></div>

    <script>
        function setCookie(cookieName, cookieValue, expirationDays) {
            var d = new Date();
            d.setTime(d.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
        }
        setCookie("token", "{{session()->get('token')}}", 1); 
    </script>
</body>
</html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body>


<div class="container flex flex-col justify-center items-center">
    <h1 class="text-2xl">Leah</h1>
    <form action="/trans" method="post" class="mt-10 flex flex-col justify-center items-center">
        <h1>Enter an English word to translate</h1>
        <input type="text" placeholder="English word">
        <input type="text" placeholder="Spanish word">
        <input type="text" placeholder="English word">
    </form>
</div>

</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;600;700&family=Roboto:wght@300;400;700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/creating.js"></script>
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    'outfit': ['Outfit', 'sans-serif'],
                    'roboto': ['Roboto', 'sans-serif']
                }
            },
            variants: {
                backgroundColor: ['hover', 'focus'],
                borderColor: ['focus', 'hover'],
                ringColor: ['focus']
            },
        }
    </script>
    <title>Document</title>
</head>
<body class="font-outfit bg-slate-100 p-10">
<?php
$servername = "127.0.0.1";
$username = "postgres";
$password = "zaq1@WSX";
ini_set('display_errors', 1);
$conn = pg_connect("host=$servername user=$username password=$password dbname=polling port=5432");

$pollOptionStyle = "
        py-2 px-4 my-4 bg-slate-200 text-slate-800 shadow-md rounded-2xl hover:scale-[101%] transition-all duration-300
        hover:bg-pink-400 hover:text-white font-outfit text-xl cursor-pointer
    ";

if (isset($_GET["title"]) && isset($_GET["options"])) {
    $options = "'{{$_GET['options']}}'";
    $created = pg_query($conn, "INSERT INTO polls (name, options, ip_protection) VALUES ('${_GET['title']}', $options, true) RETURNING id");
    $id = pg_fetch_array($created)["id"];
    header("Location: poll.php?id=$id");
}
?>
<div class='mx-5 mb-5'>
    <h1 class='text-5xl font-bold'></h1>
    <form onsubmit="return false">
        <p class='text-3xl font-outfit font-semibold text-pink-600'>Nowa ankieta</p>
        <div class="text-lg mt-4">
            <input type="text" name="options" id="options" class="hidden">
            <label for="title" class="text-xl">Tytuł ankiety</label>
            <input type="text" name="title" id="title"
                   class="px-2 py-1 ring-2 ring-slate-300 ml-3 text-lg rounded-2xl focus:ring-pink-600">
            <div class="flex items-end mb-5">
                <p class='text-2xl mt-5 font-outfit text-pink-600 mr-4'>Opcje</p>
                <button
                        class="h-8 rounded-lg px-2 border-2 font-bold border-pink-600 text-pink-600 hover:bg-pink-600 hover:text-white transition-colors"
                        onclick="return createOption()"
                >&plus;
                </button>
            </div>
            <ul id="option-list"></ul>
            <input
                    type="submit"
                    value="Stwórz"
                    onclick="return submitForm()"
                    class="h-8 rounded-lg px-2 border-2 font-bold border-pink-600 text-pink-600 hover:bg-pink-600 hover:text-white transition-colors"
            />
        </div>
    </form>
</div>
</div>
</body>
</html>

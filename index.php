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
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    'outfit': ['Outfit', 'sans-serif'],
                    'roboto': ['Roboto', 'sans-serif']
                }
            }
        }
    </script>
    <title>Document</title>
</head>
<body class="font-outfit bg-slate-100 p-10">
<div class="">
    <div class="mx-5 mb-5">
        <h1 class="text-5xl font-bold">Ankiety</h1>
        <p class="text-3xl">Tutaj znajdziesz wszystkie aktywne ankiety</p>
    </div>
    <div class="mx-5">
        <ul class="flex flex-row">
            <?php
            $servername = "127.0.0.1";
            $username = "postgres";
            $password = "zaq1@WSX";
            ini_set('display_errors', 1);
            //$conn = new mysqli($servername, $username, $password, "polling");
            $conn = pg_connect("host=$servername user=$username password=$password dbname=polling port=5432");

            //pg_exec("INSERT INTO polls VALUES ('czy dupa', ['tak', 'nie'], 'true')")
            $result = pg_query($conn, "SELECT * FROM polls");
            $result_arr = pg_fetch_all($result);

            $participateButton = "
            hover:bg-transparent hover:border-pink-600 transition-all border-2
            border-transparent font-roboto text-md bg-pink-600 px-4 font-semibold py-1 rounded-xl
        ";

            foreach ($result_arr as $row) {
                echo "
                <li class='bg-slate-600 text-white px-5 py-3 rounded-xl shadow-lg shadow-slate-400 mr-4'>
                    <h2 class='text-2xl pb-3 font-bold underline'> " . $row["name"] . "</h2>
                    <div>
                        <p class='text-slate-200'>0 głosów</p>
                    </div>
                    <div class='pt-4 pb-2'>
                        <a href='poll.php?id={$row["id"]}' class='$participateButton'>Weź udział &rarr;</a>
                    </div>
                </li>
                ";
            }
            ?>
            <li class='bg-pink-600 text-white px-5 py-3 rounded-xl shadow-lg shadow-slate-400 flex justify-between flex-col'>
                <h2 class='text-2xl pb-3 font-bold underline'>Nowa ankieta</h2>
                <div class='pt-4 pb-2 font-roboto text-md font-semibold'>
                    <a href='create_poll.php'
                       class='hover:text-pink-600 hover:bg-slate-50 transition-all border-2 border-transparent bg-slate-700 px-4 py-1 rounded-xl'>Stwórz
                    </a>
                </div>
            </li>
        </ul>
    </div>

</div>
</body>
</html>

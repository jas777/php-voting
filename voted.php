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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"/>
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
    <?php
    $servername = "127.0.0.1";
    $username = "postgres";
    $password = "zaq1@WSX";
    ini_set('display_errors', 1);
    //$conn = new mysqli($servername, $username, $password, "polling");
    $conn = pg_connect("host=$servername user=$username password=$password dbname=polling port=5432");

    if (
        !isset($_GET["id"]) ||
        preg_match("/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i", $_GET["id"]) !== 1
    ) {
        header("Location: index.php");
        die();
    }

    $result = pg_query($conn, "SELECT * FROM polls WHERE id = '{$_GET['id']}'");
    $result_arr = pg_fetch_array($result, null, PGSQL_ASSOC);
    $options = explode(",", str_replace("{", "", str_replace("}", "", $result_arr['options'])));

    $votes = pg_query($conn, "SELECT option, COUNT(option) FROM votes WHERE poll_id = '{$_GET["id"]}' GROUP BY option");
    $votes_arr = pg_fetch_all($votes, PGSQL_ASSOC);

    if (pg_num_rows($result) === 0) header("Location: index.php");
    ?>
    <div class="mx-5 mb-5">
        <h1 class="text-5xl font-bold"><?php echo $result_arr["name"] ?></h1>
        <p class="text-3xl">Wyniki ankiety</p>
    </div>
    <div class="mx-5">
        <div class="h-1/2 flex">
            <div class="text-2xl">
                <?php
                foreach ($votes_arr as $vote_result) {
                    echo "<p class='font-semibold'>{$vote_result['option']} &mdash; {$vote_result['count']}</p>";
                }
                ?>
            </div>
            <canvas id="chart"></canvas>
        </div>
        <script>
            const labels = [<?php
                foreach ($votes_arr as $vote_result) {
                    echo "'{$vote_result['option']}',";
                }
            ?>]
            const values = [<?php
                foreach ($votes_arr as $vote_result) {
                    echo "{$vote_result['count']},";
                }
            ?>]

            const getRandomRgb = () => {
                const num = Math.round(0xffffff * Math.random());
                const r = num >> 16;
                const g = num >> 8 & 255;
                const b = num & 255;
                return 'rgb(' + r + ', ' + g + ', ' + b + ')';
            }

            const chartCtx = document.getElementById("chart").getContext('2d');
            const chart = new Chart(chartCtx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: values.map(getRandomRgb)
                    }]
                },
                options: {
                    responsive: false
                }
            });
        </script>
    </div>
</div>
</body>
</html>

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
    <?php
    $servername = "127.0.0.1";
    $username = "postgres";
    $password = "zaq1@WSX";
    ini_set('display_errors', 1);
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

    $ip = $_SERVER['HTTP_CLIENT_IP'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);

    if (pg_num_rows($result) === 0 || (isset($_GET["vote"]) && !in_array($_GET["option"], $options))) header("Location: index.php");

    $ip_dup = false;

    if (isset($_GET["vote"]) && $_GET["vote"]) {
        if (!isset($_GET["option"])) header("Location: index.php");
        print_r($_COOKIE);
        if (!in_array($_GET["id"], $_COOKIE)) {
            $check = pg_query("
                SELECT EXISTS (
                    SELECT * FROM votes WHERE poll_id = '{$result_arr["id"]}' AND ip_address = '$ip'
                );
            ");
            $check_arr = pg_fetch_array($check, null, PGSQL_ASSOC);
            print_r($ip);
            if ($result_arr["ip_protection"] !== 'f' && $check_arr["exists"] !== 'f') $ip_dup = true;
            else pg_exec("INSERT INTO votes VALUES ('{$result_arr["id"]}', '{$_GET["option"]}', '$ip')");

            $dup_val = $ip_dup ? 1 : 0;
            header("Location: voted.php?ip_dup=$dup_val&id={$result_arr['id']}");
        } else {
            setcookie($_GET["id"], 1);
        }
    }

    $participateButton = "
            hover:bg-transparent hover:border-pink-600 border:text-pink-600 transition-300 transition-all border-2
            border-transparent font-roboto text-md bg-pink-600 px-4 font-semibold py-1 rounded-xl
            ";

    $options_parsed = "";

    $pollOptionStyle = "
        py-2 px-4 my-4 bg-slate-200 text-slate-800 shadow-md rounded-2xl hover:scale-[101%] transition-all duration-300
        hover:bg-pink-400 hover:text-white font-outfit text-xl cursor-pointer
    ";

    foreach ($options as $option) {
        $disabled = isset($_GET["vote"]) ? "poll.php?id={$_GET['id']}" : "poll.php?vote=1&option=$option&id={$_GET['id']}";
        $options_parsed .= "
            <li class='$pollOptionStyle'>
                <a class='poll-option block' href='$disabled'>$option</a>
            </li>
        ";
    }

    if ($ip_dup) {
        $options_parsed = "
            <h1>Ta ankieta nie pozwala na głosowanie wielokrotne!</h1>
            $options_parsed
        ";
    }

    echo "
        <div class='mx-5 mb-5'>
            <h1 class='text-5xl font-bold'>{$result_arr['name']}</h1>
            <p class='text-3xl font-outfit'>0 głosów</p>
            <div>
                $options_parsed
            </div>
        </div>
    </div>";
    ?>
</div>
</body>
</html>

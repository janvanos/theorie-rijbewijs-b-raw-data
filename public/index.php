<?php
$vraagID = 0;
if (isset($_GET['vraagID']) && is_numeric($_GET['vraagID'])) {

    $vraagID = intval($_GET['vraagID']);

    $filePath = 'assets/json/questions.json';
    if (!file_exists($filePath)) {
        die('Het JSON-bestand bestaat niet.');
    }

    if (!is_readable($filePath)) {
        die('Het JSON-bestand is niet leesbaar.');
    }

    $jsonString = file_get_contents($filePath);
    if ($jsonString === false) {
        die('Fout bij het openen van het JSON-bestand.');
    }


    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $jsonString), true);

    // var_dump($data);

    $question = $data[$vraagID]['question'];
    $image = $data[$vraagID]['image'];
    $feedback = $data[$vraagID]['feedback'];
    $type = $data[$vraagID]['type'];
    $options = $data[$vraagID]['options'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <?php
        if ($vraagID === 0) {
            echo "<h1>Start het examen</h1>";
            echo "<a href='?vraagID=1'>Start</a>";
        } else {

        ?>
            <h1><?= $question ?></h1>
            <img src="assets/img/<?= $image ?>" alt="image">
            <?php
            if ($type == 1) {
                echo "<div class='multiple'>";
                foreach ($options as $option) {
                    echo "<input type='radio' name='option' value='" . $option . "'>" . $option . "<br>";
                }
                echo "</div>";
            }
            ?>
            <button onclick="showAnswer(this)">Klik hier voor het antwoord</button>
            <p id="antwoord" class="hidden"><?= $feedback ?></p>
            <a href="?vraagID=<?= $vraagID + 1 ?>">Volgende vraag</a>
    </div>
    <script>
        function showAnswer(e) {
            var b = document.getElementById("antwoord");
            b.classList.toggle('hidden');
            if (e.innerHTML === "Klik hier voor het antwoord") {
                e.innerHTML = "Verberg het antwoord";
            } else {
                e.innerHTML = "Klik hier voor het antwoord";
            }

        }
    </script>
<?php
        } ?>
</body>

</html>
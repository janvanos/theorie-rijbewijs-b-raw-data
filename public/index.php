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

    // var_dump(count($data));

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
            <img src="assets/img/<?php
            if(file_exists("assets/img/" . $image)){
                echo $image; 
            } else {
                echo "placeholder.png";
            }
            ?>" alt="image">
<progress value="10" max="10" id="progressBar"></progress>

            <?php
            if ($type == 1 || $type == 2) {
                echo "<div class='multiple'>";
                foreach ($options as $option) {
                    if($option != ""){
                    echo "<input type='radio' name='option' value='" . $option . "'>" . $option . "<br>";
                }}
                echo "</div>";
            } else {
                echo "<div class='single'>";
                echo "<textarea></textarea>";
                echo "</div>";
            }
            ?>
            <button id="answer" onclick="showAnswer(this)">Klik hier voor het antwoord</button>
            <p id="antwoord" class="hidden"><?= $feedback ?></p>
<ul class="pagination">
<?php


    $total_pages = ceil(count($data));
    
    $links = "";
    if ($total_pages >= 1 && $vraagID <= $total_pages) {
         $i = max(1, $vraagID - 4);

         if($i < $vraagID){
         $links .= "<li class='next'><a href='?vraagID=$i' class='page-link'>Vorige vraag</a></li>";   
        }
                 
        for (; $i < min($vraagID + 5, $total_pages); $i++) {
            if($i==$vraagID){
            $links .= "<li class='page-item active'><a href='?vraagID=$i' class='page-link'>$i</a></li>";   
            }else{
            $links .= "<li class='page-item'><a href='?vraagID=$i' class='page-link'>$i</a></li>";
            }
        }
        $links .= "<li class='next'><a href='?vraagID=$i' class='page-link'>Volgende vraag</a></li>";   

        echo $links;
    }

        
            ?>
</ul>
           
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

        var timeleft = 10;
var downloadTimer = setInterval(function(){
  if(timeleft <= 0){
    clearInterval(downloadTimer);
    document.getElementById("answer").click();
  }
  document.getElementById("progressBar").value = timeleft-1;
  timeleft -= 1;
}, 1000);
    </script>
<?php
   
        
        } ?>
</body>

</html>
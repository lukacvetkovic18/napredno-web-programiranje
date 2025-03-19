<style>
    .container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }
    .profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }
    .profile:hover {
        transform: scale(1.05);
        cursor: pointer;
        background-color:rgb(200, 200, 200);
    }
    .profile img {
        float: left;
        margin-right: 10px;
    }
    .profile h2 {
        margin-top: 0;
    }
    p {
        text-align: center;
    }
</style>

<?php

$xmlContent = file_get_contents('LV2.xml');
$xmlContent = str_replace('&', '&amp;', $xmlContent);
$xml = simplexml_load_string($xmlContent);

if ($xml === false) {
    die('Greška pri učitavanju XML datoteke');
}

echo "<div class='container'>";

foreach ($xml->record as $record) {
    echo "<div class='profile'>";
    echo "<img src='{$record->slika}' alt='Slika osobe' width='50' height='50'><br>";
    echo "<h2>{$record->ime} {$record->prezime}</h2>";
    echo "<p><strong>Email:</strong> {$record->email}</p>";
    echo "<p><strong>Životopis:</strong> {$record->zivotopis}</p>";
    echo "</div>";
}

echo "</div>";
?>

<?php
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include('./simple_html_dom.php');

interface iRadovi {
    public function create($data);
    public function save();
    public function read();
}

class DiplomskiRadovi implements iRadovi {
    private $naziv_rada;
    private $tekst_rada;
    private $link_rada;
    private $oib_tvrtke;

    public function create($data) {
        $this->naziv_rada = $data['naziv_rada'];
        $this->tekst_rada = $data['tekst_rada'];
        $this->link_rada = $data['link_rada'];
        $this->oib_tvrtke = $data['oib_tvrtke'];
    }

    public function save() {
        $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO diplomski_radovi (naziv_rada, tekst_rada, link_rada, oib_tvrtke) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $this->naziv_rada, $this->tekst_rada, $this->link_rada, $this->oib_tvrtke);
        
        if ($stmt->execute()) {
            echo "Rad uspješno spremljen u bazu podataka.\n";
        } else {
            echo "Greška pri spremanju rada: " . $stmt->error . "\n";
        }

        $stmt->close();
        $conn->close();
    }

    public function read() {
        $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM diplomski_radovi";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "Naziv rada: " . $row["naziv_rada"] . "\n";
                echo "Link rada: " . $row["link_rada"] . "\n";
                echo "OIB tvrtke: " . $row["oib_tvrtke"] . "\n";
                echo "Tekst rada: " . $row["tekst_rada"] . "\n\n";
            }
        } else {
            echo "Nema podataka u bazi.\n";
        }

        $conn->close();
    }
}

// for ($page = 2; $page <= 6; $page++) {
//     $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/{$page}/";
//     $html = file_get_html($url);

//     foreach($html->find('article') as $article) {
//         $img = $article->find('img', 0);
//         $link = $article->find('h2.entry-title a', 0);
        
//         if ($img && $link) {
//             $oib_tvrtke = preg_replace('/[^0-9]/', '', $img->src);
//             $naziv_rada = $link->plaintext;
//             $link_rada = $link->href;
            
//             $html_rada = file_get_html($link_rada);
//             $tekst_rada = $html_rada->find('.post-content', 0)->plaintext;

//             $rad = new DiplomskiRadovi();
//             $rad->create([
//                 'naziv_rada' => $naziv_rada,
//                 'tekst_rada' => $tekst_rada,
//                 'link_rada' => $link_rada,
//                 'oib_tvrtke' => $oib_tvrtke
//             ]);

//             $rad->save();
//         }
//     }
// }

$radovi = new DiplomskiRadovi();
$radovi->read();

?>

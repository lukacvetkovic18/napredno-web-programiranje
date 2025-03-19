<?php 
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$db_name = $_ENV['DB_NAME'];

$dir = "./backup/$db_name";

if (!is_dir($dir)) {
    if (!@mkdir($dir)) {
        die("<p>Ne možemo stvoriti direktorij $dir.</p></body></html>");
    }
}

$time = time();
$dbc = @mysqli_connect($host, $username, $password, $db_name) OR die("<p>Ne možemo se spojiti na bazu $db_name.</p></body></html>");

$r = mysqli_query($dbc, 'SHOW TABLES');
if (mysqli_num_rows($r) > 0) {
    echo "<p>Backup za bazu podataka '$db_name'.</p>";
    while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {
        $q = "SELECT * FROM $table";
        $r2 = mysqli_query($dbc, $q);
        $columns = $r2->fetch_fields();
        if (mysqli_num_rows($r2) > 0) {
            if ($fp = fopen ("$dir/{$table}_{$time}.txt", 'w9')) {
                while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                    fwrite($fp, "INSERT INTO $db_name (");
                    foreach($columns as $column) {
                        fwrite($fp, "$column->name");
                        if ($column != end($columns)) {
                            fwrite($fp, ", ");
                        }
                    }
                    fwrite($fp, ")\r\nVALUES (");
                    foreach ($row as $value) {
                        $value = addslashes($value);
                        fwrite ($fp, "'$value'");
                        if ($value != end($row)) {
                            fwrite($fp, ", ");
                        } else {
                            fwrite($fp, ")\";");
                        }
                    }
                    fwrite ($fp, "\r\n");

                }
                fclose($fp);
                echo "<p>Tablica '$table' je pohranjena.</p>";
                if ($fp2 = gzopen("$dir/{$table}_{$time}.sql.gz", 'w9')) {
                    gzwrite($fp2, file_get_contents("$dir/{$table}_{$time}.txt"));
                    gzclose($fp2);
                } else {
                    echo "<p>Datoteka $dir/{$table}_{$time}.sql.gz e ne može otvoriti.</p>";
                    break;
                }

            } else {
                echo "<p>Datoteka $dir/{$table}_{$time}.txt se ne može otvoriti.</p>";
                break;
            }

        }
        
    }

} else {
    echo "<p>Baza $db_name ne sadrži tablice.</p>";
}

?>

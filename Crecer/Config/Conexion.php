
<?php
// conexión con la base de datos: bd_psicologia
    class Database {

        private $hostname = "localhost"; 
        private $database = "bd_psicologia";
        private $username = "root";
        private $password = "";
        private $charset = "utf8";

        function conectar() {

            try {
                $conectar = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;
                $options =[
                    PDO ::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO ::ATTR_EMULATE_PREPARES => false
                ];

                $pdo = new PDO($conectar, $this->username, $this->password, $options);
                return $pdo;
            } catch (PDOException $e) {
                echo 'Error al conectar con la base de datos' . $e->getMessage();
                exit;
            }
        }
    }
?>

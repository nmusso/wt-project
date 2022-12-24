<?php
require_once("bootstrap.php");
$result["registerOK"] = false;
// Recupero i dati dal form di inserimento.
$username = $_POST['username'];
$password = $_POST['password'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$dataNascita = $_POST['dataNascita'];
$bio = $_POST['bio'];
$email = $_POST['email'];
// Crea una chiave casuale
$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
// Crea una password usando la chiave appena creata.
$password = hash('sha512', $password . $random_salt);
// Inserisci a questo punto il codice SQL per eseguire la INSERT nel tuo database
// Assicurati di usare statement SQL 'prepared'.
$checkFields = $dbh->checkUniqueUser($username, $email);

list($checkImage, $msg) = uploadImage(UPLOAD_DIR, $_FILES["imgProfilo"]);

if ($checkFields != false && $checkImage != false) {
   $id = $dbh->insertUser($username, $email, $password, $random_salt, $nome, $cognome, $dataNascita, $bio, $msg);

   if ($id != false) {
      $result["registerOK"] = true;
   } else {
      $result["erroreRegister"] = "Registration gone wrong. Check all the fields and retry.";
   }
} else {
   if (!$checkFields) {
      $result["erroreRegister"] = "An account with this username or email already exists. Please login.";
   } else {
      $result["erroreRegister"] = $msg;
   }
}

header('Content-Type: application/json');
echo json_encode($result);
?>
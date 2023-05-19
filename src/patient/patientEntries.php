<?php include_once("./Patient.php");
session_start();
$conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
$patient = new Patient($conn);


if(isset($_POST["heart-rate"]) && isset($_POST["systolic"]) && isset($_POST["diastolic"])){
    $patient->insert_hr_bp($_POST["heart-rate"], $_POST["systolic"], $_POST["diastolic"], $_SESSION["user_id"]);
}

if(isset($_POST["temperature"])){
    $patient->addTemperature($_POST["temperature"], $_SESSION["user_id"]);
}

if(isset($_POST["oxygen"])){
    $patient->insert_blood_oxygen($_POST["oxygen"], $_SESSION["user_id"]);
}

if(isset($_POST["glucose"])){
    $patient->insertBloodGlucose($_POST["glucose"], $_SESSION["user_id"]);
}

if( isset($_POST["fetal-weight"]) && isset($_POST["fetal-heart-rate"])){
    $patient->insertOrUpdateFetusRecord($_SESSION["user_id"], $_POST["fetal-weight"], $_POST["fetal-heart-rate"]);
}

?>
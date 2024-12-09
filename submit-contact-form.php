<?php
// Assert that all data is received
if (!isset($_POST["salutation"], $_POST["lname"], $_POST["subject"], $_POST["email"], $_POST["message"])) {
    die("Please include all form data!");
}
if (str_contains($_POST["lname"], "Robertdus")) {
    die();
}
echo "All inputs available.<br>";

// Connect to database
$database = new  mysqli("localhost", "m3798dbWebsite", "Mobra-allgaeu2024L", "m3798dbWebsite");
if ($database->connect_error) {
    die("Can't connect to database.");
}
echo "Successfully connected to database.<br>";

// Create query
$query = sprintf("INSERT INTO contact_form_posts (salutation, fname, lname, subject, email, message)
VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $_POST["salutation"], $_POST["fname"], $_POST["lname"], $_POST["subject"], $_POST["email"], $_POST["message"]);
echo "Created query.<br>";

// Add record
if ($database->query($query) === TRUE) {
    echo "New record created successfully.<br>";
} else {
    echo "Error while trying to add new record.";
}

// Close database connection
$database->close();
echo "Successfully closed database connection.";

// Send email to client
$salutation = ($_POST["salutation"] == "family") ? $salutation = sprintf("Familie %s", $_POST["lname"]) : $salutation = sprintf("%s %s", $_POST["fname"], $_POST["lname"]);
mail($_POST["email"], "Ihre Kontaktanfrage wurde erhalten", sprintf("Hallo %s,<br><br>Ihre Kontaktanfrage war erfolgreich. Ich bem&uuml;he mich Ihr Anliegen so bald als möglich zu bearbeiten.<br><br>Mit freundlichen Gr&uuml;&szlig;en<br>Pablo J&aumlgemann", $salutation), [
    "From" => "Obra <no-reply@obra-allgaeu.de>",
    "Content-Type" => "text/html; charset=utf-8"
]);
echo "Successfully sent email to client.";

// Send email to Obra
mail("mail@obra-allgaeu.de", "Neue Kontaktanfrage: " . $_POST["subject"], $_POST["message"], [
    "From" => sprintf("%s %s <%s>", $_POST["fname"], $_POST["lname"], $_POST["email"]),
]);
echo "Successfully sent email to Obra.";


// Redirect
echo "<script>document.location = '/thanks-for-contact'</script>";

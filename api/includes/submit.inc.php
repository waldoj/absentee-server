<?php

/*
 * Create an empty array to encode our response.
 */
$response = array();

/*
 * If there's no file, there's nothing to be done.
 */
if (!isset($uploaded_file))
{

    $response['valid'] = FALSE;
    $response['errors'] = 'No file provided.';
    echo json_encode($response);
    exit();

}

/*
 * Change the variable name for the absentee ballot application.
 */
$ab = $uploaded_file;
unset($uploaded_file);

/*
 * Identify the registrar to whom this application should be sent.
 */
$gnis_id = $ab->election->locality_gnis;
$registrars = json_decode(file_get_contents('includes/registrars.json'));
$send_to = $registrars->$gnis_id->email;

/*
 * Save this application.
 */
$dir = 'applications/';
$filename = substr(md5(json_encode($ab)), 0, 7);
$result = file_put_contents($dir . $filename, json_encode($ab));
if ($result === FALSE)
{
	$response['errors'] = TRUE;
}

/*
 * Send a response to the browser.
 */
$json = json_encode($response);
if ($json === FALSE)
{
	$response['errors'] = TRUE;
	$json = json_encode($response);
}
echo $json;

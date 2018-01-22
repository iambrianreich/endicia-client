<?php

require_once('vendor/autoload.php');

use RWC\Endicia\Client;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\ChangePassPhraseRequest;

$temp = 'u42ZXpc2KcV59X3ygSeh';
$accountId = getenv('ENDICIA_ACCOUNT_ID');
$passPhrase = getenv('ENDICIA_PASSPHRASE');
$client = new Client(Client::MODE_SANDBOX);
echo "Creating credential for $accountId and $temp\n";
$ci = CertifiedIntermediary::createFromCredentials($accountId, $temp);

echo "Changing Pass Phrase from $temp to $passPhrase\n";
$request = new ChangePassPhraseRequest($client->getSandboxRequesterId(), $ci, $passPhrase);
$response = $client->changePassPhrase($request);

if ($response->isSuccessful()) {
    echo "Passphrase changed to $passPhrase.\n";
} else {
    echo "Failed to change passphrase: " . $response->getErrorMessage() . "\n";
}

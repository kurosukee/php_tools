<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;  
use Aws\Exception\AwsException;

function setEnv() {
    $fp = fopen('.env', 'r');
    while (!feof($fp)) {
      $string = fgets($fp);
      if (empty($string)) continue;
      $env = explode('=', trim($string));
      define($env[0], $env[1]);
    }
    fclose($fp);
}

function getS3Client() {
    $credentials = [
        'key' => ACCESS_KEY,
        'secret' => SECRET_KEY,
    ];

    $bucket_version = empty(BUCKET_VERSION) ? 'latest' : BUCKET_VERSION;
    $bucket_region = empty(BUCKET_REGION) ? 'ap-northeast-1' : BUCKET_REGION;
    $bucket_name = BUCKET_NAME;

    $s3 = new S3Client([
        'credentials' => $credentials,
        'region'  => $bucket_region,
        'version' => $bucket_version,
    ]);
    return $s3;
}

function getPostsForm($s3Client) {
    $formInputs = ['acl' => 'public-read'];
    $options = [
        ['acl' => 'public-read'],
        ['bucket' => BUCKET_NAME],
        ['starts-with', '$key', 'hoge/'],
    ];

    $expires = '+5 minutes';

    $postObject = new \Aws\S3\PostObjectV4(
        $s3Client,
        BUCKET_NAME,
        $formInputs,
        $options,
        $expires
    );
    $formAttributes = $postObject->getFormAttributes();
    $formInputs = $postObject->getFormInputs();

    return ['attributes' => $formAttributes, 'inputs' => $formInputs];
}


// ===== main =====
setEnv();
$s3Client = getS3Client();
$form = getPostsForm($s3Client);

foreach ($form['attributes'] as $key => $value) {
    echo sprintf("%s:%s\n", $key, $value);
}
echo "\n";
foreach ($form['inputs'] as $key => $value) {
    echo sprintf("%s:%s\n", $key, $value);
}

?>
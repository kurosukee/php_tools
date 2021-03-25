<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\CommandPool;

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

function getPresignUrl($s3Client) {
    $command = $s3Client->getCommand('PutObject', array(
        'Bucket' => BUCKET_NAME,
        // 'ContentType' => 'application/x-www-form-urlencoded',
        // 'ACL' => 'public-read',
        'Key'    => 'hoge',
    ));
    $request = $s3Client->createPresignedRequest($command, '+1 minutes');
    return $request->getUri();
}


// ===== main =====
setEnv();
$s3Client = getS3Client();
$url = getPresignUrl($s3Client);
echo sprintf("%s\n", $url);

?>
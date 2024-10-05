<?php declare(strict_types = 1);

use App\Utils;
use GuzzleHttp\Client;
use Instagram\Api;
use Instagram\Model\Media;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require __DIR__ . '/../vendor/autoload.php';

Utils::dotenv();
Utils::debugger();

$tmpDir = Utils::getTmp();
[$username, $password] = Utils::getCredentials();
$proxy = Utils::getProxy();

$count = intval($_GET['count'] ?? 10);
$account = $_GET['_user'] ?? null;

if (empty($account)) {
	Utils::error('Missing username. Try photos.php?_user=nickname');
}

$clientOptions = [];
if ($proxy !== null) {
	$clientOptions['proxy'] = [
		'https' => $proxy,
	];
}

$client = new Client($clientOptions);
$cachePool = new FilesystemAdapter('Instagram', 0, $tmpDir);

$api = new Api($cachePool, $client);
$api->login($username, $password);
$profile = $api->getProfile($account);
$profile = $api->getMoreMedias($profile);

$medias = $profile->getMedias();

$output = array_map(function (Media $media) {
	return Utils::dump($media);
}, $medias);

Utils::json($output);

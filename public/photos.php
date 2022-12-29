<?php declare(strict_types = 1);

use App\Utils;
use Instagram\Api;
use Instagram\Model\Media;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require __DIR__ . '/../vendor/autoload.php';

Utils::dotenv();
Utils::debugger();

$tmpDir = Utils::getTmp();
[$username, $password] = Utils::getCredentials();

$count = intval($_GET['count'] ?? 10);
$account = $_GET['_user'] ?? null;

if (empty($account)) {
	Utils::error('Missing username. Try photos.php?_user=nickname');
}

$cachePool = new FilesystemAdapter('Instagram', 0, $tmpDir);

$api = new Api($cachePool);
$api->login($username, $password);
$profile = $api->getProfile($account);

$medias = $profile->getMedias();

$output = array_map(function (Media $media) {
	return Utils::dump($media);
}, $medias);

Utils::json($output);

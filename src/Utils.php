<?php declare(strict_types = 1);

namespace App;

use ReflectionClass;
use Symfony\Component\Dotenv\Dotenv;
use Tracy\Debugger;

final class Utils
{

	public static function dotenv(): void
	{
		$dotenv = new Dotenv();
		$dotenv->loadEnv(__DIR__ . '/../.env');
	}

	public static function debugger(): void
	{
		Debugger::enable(Utils::isDebug() ? Debugger::DEBUG : Debugger::PRODUCTION, Utils::getTmp());
	}

	public static function getCredentials(): array
	{
		return [$_ENV['INSTAGRAM_USERNAME'], $_ENV['INSTAGRAM_PASSWORD']];
	}

	public static function getTmp(): string
	{
		return __DIR__ . '/../tmp';
	}

	public static function isDebug(): bool
	{
		return $_ENV['DEBUG'] === '1';
	}

	public static function dump($obj): mixed
	{
		$output = [];

		$rc = new ReflectionClass($obj);
		foreach ($rc->getProperties() as $property) {
			$property->setAccessible(true);
			$output[$property->getName()] = $property->getValue($obj);
		}

		return $output;
	}

	public static function error($error, $code = 400): void
	{
		self::json(['error' => $error], $code);
	}

	public static function json($data, $code = 200): never
	{
		Utils::header('content-type', 'application/json');
		Utils::code($code);
		echo json_encode($data);
		exit();
	}

	public static function cors(): void
	{
		Utils::header('Access-Control-Allow-Origin', '*');
		Utils::header('Access-Control-Allow-Methods', '*');
		Utils::header('Access-Control-Allow-Headers', '*');
	}

	public static function header($name, $value): void
	{
		header($name . ': ' . $value, false);
	}

	public static function code(int $code): void
	{
		http_response_code($code);
	}

}

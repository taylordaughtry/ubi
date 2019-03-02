<?php

namespace Silmaril\Ubi\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

trait InteractsWithUbi
{
	protected $user;

	protected $password;

	/**
	 * Make a request to the Controller software.
	 *
	 * TODO: Confirm that the user is logged in if using a protected endpoint.
	 *
	 * @param  string $endpoint [description]
	 * @param  array  $data     [description]
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function call(string $endpoint, array $data)
	{
		$client = new Client([
			'base_uri' => 'https://127.0.0.1:8443',
		]);

		if (! $this->hasValidSession()) {
			$this->refreshSession();
		}

		return $client->request(
			'POST',
			$data,
			[
				'headers' => [
					'Content-Type: application/json',
				]
			]
		);
	}

	/**
	 * Refresh the user's cookie to continue using protected endpoints.
	 *
	 * TODO: Parse the cookie response and save into the $cookie property.
	 *
	 * @return void
	 */
	public function refreshSession()
	{
		$client = new Client([
			'base_uri' => 'https://127.0.0.1:8443',
		]);

		$response = $client->request(
			'POST',
			'/api/login',
			[
				'username' => $this->user,
				'password' => $this->password,
			]
		);
	}
}

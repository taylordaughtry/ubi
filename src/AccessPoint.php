<?php

namespace Silmaril\Ubi;

use Silmaril\Ubi\Traits\InteractsWithUbi;

class AccessPoint
{
	use InteractsWithUbi;

	public $id;

	public $macAddress;

	private $locating;

	/**
	 * Instantiate a new Access Point.
	 *
	 * TODO: Fetch and cache the AP's properties when it's constructed.
	 * TODO: Automatically get an AP's ID via listDevices()
	 *
	 * @param string $macAddress
	 * @param string $id The Access Point's ID.
	 */
	public function __construct(string $macAddress, string $id)
	{
		$this->macAddress = $macAddress;

		$this->id = $id;

		$this->locating = false;
	}

	/**
	 * Create a new AccessPoint instance.
	 *
	 * @param string $macAddress
	 *
	 * @return \Silmaril\Ubi\AccessPoint
	 */
	public static function create(string $macAddress, string $id)
	{
		return new static($macAddress, $id);
	}

	/**
	 * Restart this AP. You'll need to be logged in before running this.
	 *
	 * @return \Silmaril\Ubi\AccessPoint
	 */
	public function restart()
	{
		$this->call('/cmd/devmgr', [
			'restart',
			$this->macAddress
		]);

		return $this;
	}

	/**
	 * Start or stop flashing the LED on this AP.
	 *
	 * @return \Silmaril\Ubi\AccessPoint
	 */
	public function locate() {
		$this->locating = ! $this->locating;

		$this->call('/cmd/devmgr', [
			$this->locating ? 'set-locate' : 'unset-locate',
			$this->macAddress
		]);

		return $this;
	}

	/**
	 * Rename this AP.
	 *
	 * @return \Silmaril\Ubi\AccessPoint
	 */
	public function rename(string $name) {
        $this->call("/upd/device{$this->id}", [
        	'name' => $name
        ]);

        return $this;
	}

	/**
	 * Enable this AP.
	 *
	 * @return \Silmaril\Ubi\AccessPoint
	 */
	public function enable() {
		$this->call("/rest/device{$this->id}", [
			'disabled' => false,
		]);

		return $this;
	}

	/**
	 * Disable this AP.
	 *
	 * @return \Silmaril\Ubi\AccessPoint
	 */
	public function disable() {
		$this->call("/rest/device{$this->id}", [
			'disabled' => true,
		]);

		return $this;
	}

	/**
	 * Set radio parameters for this AP.
	 *
	 * @return \Silmaril\Ubi\AccessPoint
	 */
	public function setRadioParams() {
		$this->call("/upd/device/{$this->id}", [
			'radio_table' => [
				'radio' => '',
				'channel' => '',
				'ht' => '',
				'tx_power_mode' => '',
				'tx_power' => '',
			]
		]);

		return $this;
	}
}

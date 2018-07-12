<?php

/**
 * Utech\Cti
 *
 * A Simple Utech-CTI client based on clue/socket-raw library
 *
 * @category   Library
 * @package    Utech\Cti
 * @license    MIT
 */

namespace Utech\Cti;

use \InvalidArgumentException;


/**
* This class extends \Socket\Raw\Socket and implement a Utech-CTI commands
*
* @author     Rafael Bozzetti <rafaelbozzetti@gmail.com>
*/

class Socket extends \Socket\Raw\Socket
{

	/**
	* Login a CTI User. Must be performed before sending commands.
	*
	* @param String $user A valid CTI user.
	* @param String $password User password.
	*
	* @return string
	**/

	public function login($user, $password)
	{
		$this->write("LOGIN USER:$user PASSWORD:$password\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Logoff a CTI User.
	*
	* @return string
	**/
	public function logoff()
	{
		$this->write("LOGOFF\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Monitors the TCP conenction status.
	*
	* @return string
	**/
	public function status()
	{
		$this->write("STATUS\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Make a call to a destination.
	* @param String $destination Destination number.
	*
	* @return string
	**/

	public function make_call($destination)
	{
		$this->write("DIAL TO:$destination\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Redial to the last number dialed by the user cti.
	* @param String $calledid Unique call's identification.
	*
	* @return string
	**/

	public function redial($calleid)
	{
		$this->write("ANSWER CALLEID:$calleid\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Request incoming call forwarding.
	* @param String $calledid Unique call's identification.
	*
	* @return string
	**/

	public function deflect($callid)
	{
		$this->write("DEFLECT CALLEID:$calleid\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Drop user's call.
	* @param String $calledid Unique call's identification.
	*
	* @return string
	**/

	public function drop($callid)
	{
		$this->write("DROP CALLEID:$calleid\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Hold user's call.
	* @param String $calledid Unique call's identification.
	*
	* @return string
	**/

	public function hold($callid)
	{
		$this->write("HOLD CALLEID:$calleid\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Unhold user's call.
	* @param String $calledid Unique call's identification.
	*
	* @return string
	**/

	public function retrieve($callid)
	{
		$this->write("RETRIEVE CALLEID:$calleid\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Alternate between active calls.
	*
	* @return string
	**/

	public function alternate()
	{
		$this->write("ALTERNATE\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Hangup the current call and alternate to on hold calls
	*
	* @return string
	**/

	public function reconnect()
	{
		$this->write("RECONNECT\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Conference
	*
	* @return string
	**/

	public function conference()
	{
		$this->write("CONFERENCE\r\n\r\n");
		return $this->read(2048);
	}


	/**
	* Make a blind transfer to destination, without consultation
	*
	* @param String $calledid Unique call's identification.
	* @param String $destination destination extension.
	*
	* @return string
	**/

	public function blind_transfer($callid, $destination)
	{
		$this->write("BLIND TRANSFER CALLID:$callid TO:$destination\r\n\r\n");
		return $this->read(2048);
	}

}
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

	/* Parser contants definition */
    const READING_COMMAND = 0;
    const READING_ATTRIBUTE = 1;
    const READING_VALUE = 2;

	/**
	 * Utech's C++ code ported to PHP
	 */
	public function parser($cmd, $len) {

	    $state = READING_COMMAND;
	    $p = Array();
	    $l = $cmd;
	    $v = null;
	    $k = 0;
	    $j = 0;

	    for ($i = 0; $i <= $len; $i++) {
	        if ($cmd[$i] == ' ' || $i == $len) {
	            switch ($state) {
	                case READING_COMMAND:
	                    $p[$k++] = substr($l, 0, $i);
	                    $l = substr($cmd, $i + 1);
	                    $state = READING_ATTRIBUTE;
	                    $j = $i + 1;
	                    break;
	                case READING_ATTRIBUTE:
	                    $l = substr($cmd, $i + 1);
	                    $j = $i + 1;
	                    break;
	                case READING_VALUE:
	                    if ($v[0] != '"' || $cmd[$i-1] == '"') {
	                        $p[$k++] = substr($l, 0, $i - $j);
	                        $l = substr($cmd, $i + 1);
	                        $state = READING_ATTRIBUTE;
	                        $j = $i + 1;
	                    }
	                    break;
	            }
	        } else if ($cmd[$i] == ':') {
	            if ($state == READING_ATTRIBUTE) {
	                $v = substr($cmd, $i + 1);
	                $state = READING_VALUE;
	            }
	        }
	    }
	    return $p;
	}

	/**
	* Send Command method.
	*
	* @param String $command Command to send .
	* @param Array  $parameters Parameters key/value to send.
	*
	* @return Array
	**/	
	public function send_command($cmd)
	{
		switch($cmd['command']) {
			case 'login':
				$return = $this->login($cmd['user'], $cmd['password']);

			break;
			case 'logoff':
				$return = $this->logoff();

			break;
			case 'status':
				$return = $this->status();

			break;
			case 'make_call':
				$return = $this->make_call($cmd['destination']);

			break;
			case 'redial':
				$return = $this->redial($cmd['callid']);

			break;
			case 'deflect':
				$return = $this->deflect($cmd['callid']);

			break;
			case 'drop':
				$return = $this->drop($cmd['callid']);

			break;
			case 'hold':
				$return = $this->hold($cmd['callid']);

			break;
			case 'retrieve':
				$return = $this->retrieve($cmd['callid']);

			break;
			case 'alternate':
				$return = $this->status();

			break;
			case 'reconnect':
				$return = $this->reconnect();

			break;
			case 'conference':
				$return = $this->conference();

			break;
			case 'blind_transfer':
				$return = $this->blind_transfer($cmd['callid'], $cmd['conference']);

			break;
		}	

		return $this->parser($return, strlen($return));
	}

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
		return $this->read(8192);
	}


	/**
	* Logoff a CTI User.
	*
	* @return string
	**/
	public function logoff()
	{
		$this->write("LOGOFF\r\n\r\n");
		return $this->read(8192);
	}


	/**
	* Monitors the TCP conenction status.
	*
	* @return string
	**/
	public function status()
	{
		$this->write("STATUS\r\n\r\n");
		return $this->read(8192);
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

	public function redial($callid)
	{
		$this->write("ANSWER CALLEID:$callid\r\n\r\n");
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
		$this->write("DEFLECT CALLEID:$callid\r\n\r\n");
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
		$this->write("DROP CALLEID:$callid\r\n\r\n");
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
		$this->write("HOLD CALLEID:$callid\r\n\r\n");
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
		$this->write("RETRIEVE CALLEID:$callid\r\n\r\n");
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
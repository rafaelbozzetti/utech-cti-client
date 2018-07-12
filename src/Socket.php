<?php

namespace Utech\Cti;

use \InvalidArgumentException;

class Socket extends \Socket\Raw\Socket
{

	public function login($user, $password)
	{
		$this->write("LOGIN USER:$user PASSWORD:$password\r\n\r\n");
		return $this->read(8192);
	}
}
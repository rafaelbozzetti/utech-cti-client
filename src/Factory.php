<?php

namespace Utech\Cti;

use \InvalidArgumentException;

class Factory extends \Socket\Raw\Factory
{
    /**
     * create low level socket with given arguments
     * Rewrited from \Socket\Raw\Factory
     *
     * @param int $domain
     * @param int $type
     * @param int $protocol
     * @return \Socket\Raw\Socket
     * @throws Exception if creating socket fails
     * @uses socket_create()
     */
    public function create($domain, $type, $protocol)
    {
        $sock = @socket_create($domain, $type, $protocol);
        if ($sock === false) {
            throw Exception::createFromGlobalSocketOperation('Unable to create socket');
        }
        return new Socket($sock);
    }
}
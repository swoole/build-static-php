<?php

use SwooleCli\Preprocessor;
use SwooleCli\Extension;

return function (Preprocessor $p) {
    $p->addExtension(
        (new Extension('apcu'))
            ->withOptions('--enable-apcu')
            ->withPeclVersion('5.1.28')
            ->withFileHash('md5', '69e2063b28725aca0ce6cac087a5d2bc')
            ->withHomePage('https://github.com/krakjoe/apcu.git')
            ->withManual('https://www.php.net/apcu')
            ->withLicense('https://github.com/krakjoe/apcu/blob/master/LICENSE', Extension::LICENSE_PHP)
    );
};

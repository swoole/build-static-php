<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $bzip2_prefix = BZIP2_PREFIX;
    $p->addLibrary(
        (new Library('bzip2'))
            ->withHomePage('https://www.sourceware.org/bzip2/')
            ->withManual('https://www.sourceware.org/bzip2/docs.html')
            ->withUrl('https://sourceware.org/pub/bzip2/bzip2-1.0.8.tar.gz')
            ->withPrefix($bzip2_prefix)
            ->withMakeOptions('PREFIX=' . $bzip2_prefix)
            ->withMakeInstallOptions('PREFIX=' . $bzip2_prefix)
            ->withLicense('https://www.sourceware.org/bzip2/', Library::LICENSE_BSD)
            ->withBinPath($bzip2_prefix . '/bin/')
            ->withLdflags('-L' . $bzip2_prefix . '/lib')
            ->withPkgConfig('')
    );
    $p->withVariable('CPPFLAGS', '$CPPFLAGS -I' . $bzip2_prefix . '/include');
    $p->withVariable('LDFLAGS', '$LDFLAGS -L' . $bzip2_prefix . '/lib');
    $p->withVariable('LIBS', '$LIBS -lbz2');
};

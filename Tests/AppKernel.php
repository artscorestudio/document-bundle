<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle()
        ];
        
        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
        	
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return __DIR__.'/Fixtures/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return __DIR__.'/Fixtures/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $loader->load($this->getRootDir().'/config/config_windows_'.$this->getEnvironment().'.yml');
        } else {
            $loader->load($this->getRootDir().'/config/config_unix_'.$this->getEnvironment().'.yml');
        }
    }
}

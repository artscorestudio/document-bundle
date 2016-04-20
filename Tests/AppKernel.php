<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * AppKernel
 *  
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\KernelInterface::registerBundles()
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
        	new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
        	new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
        	
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
        		
        	new ASF\CoreBundle\ASFCoreBundle(),
        	new ASF\DocumentBundle\ASFDocumentBundle()
        );

        return $bundles;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\Kernel::getRootDir()
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\Kernel::getCacheDir()
     */
    public function getCacheDir()
    {
        return __DIR__.'/Fixtures/var/cache/'.$this->getEnvironment();
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\Kernel::getLogDir()
     */
    public function getLogDir()
    {
        return __DIR__.'/Fixtures/var/logs';
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\KernelInterface::registerContainerConfiguration()
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $platform = 'windows';
        } else {
            $platform = 'unix';
        }
        
        $loader->load($this->getRootDir().'/config/config_'.$platform.'.yml');
    }
}

<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
        	new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        	new FOS\UserBundle\FOSUserBundle(),
	        new Sonata\jQueryBundle\SonatajQueryBundle(),   
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new SunCat\MobileDetectBundle\MobileDetectBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            //new Orkestra\APCBundle\OrkestraAPCBundle(),
            new Kraken\AdminBundle\KrakenAdminBundle(),
            new Kraken\UserBundle\KrakenUserBundle(),
            new Bc\Bundle\BootstrapBundle\BcBootstrapBundle(),
            /** Other bundles */
            new BeSimple\I18nRoutingBundle\BeSimpleI18nRoutingBundle(),
<<<<<<< HEAD
            new Epidoux\HybridAuthBundle\epidouxHybridAuthBundle(),
=======
>>>>>>> 5f6e559f1b90a9e3444c687181ceba920a632883
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

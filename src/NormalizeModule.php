<?php
namespace Corley\NormalizeModule;

use Corley\Modular\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class NormalizeModule implements ModuleInterface
{
    private $options;

    public function __construct(array $options = [])
    {
        $this->options = array_replace_recursive([
            'cache' => "/tmp/NormalizeCachedContainer.php",
            'debug' => true,
        ], $options);
    }

    public function getContainer()
    {
        $file = $this->options['cache'];

        $container = null;
        if (!$this->options['debug'] && file_exists($file)) {
			require_once $file;
			$container = new \NormalizeCachedContainer();
        } else {
            $container = new ContainerBuilder();

            $loader = new XmlFileLoader($container, new FileLocator(realpath(__DIR__  . '/../configs')));
            $loader->load(realpath(__DIR__ . '/../configs/services.xml'));

            $container->compile();

            if (!$this->options['debug']) {
                $dumper = new PhpDumper($container);
                file_put_contents($file, $dumper->dump(['class' => 'NormalizeCachedContainer']));
            }
        }

        return $container;
    }
}

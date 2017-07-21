<?php
namespace Corley\NormalizeModule;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Normalize\Normalize;
use Symfony\Component\Serializer\Serializer;

class NormalizeModuleTest extends TestCase
{
    public function testCreateBaseNormalizeService()
    {
        $module = new NormalizeModule();
        $container = $module->getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertTrue($container->has('serializer'));

        $event = $container->get('serializer');

        $this->assertInstanceOf(Serializer::class, $event);
    }

    public function testCreateBaseNormalizeServiceWithRam()
    {
        $module = new NormalizeModule([
            'cache' => '/tmp/Cache.php',
            'debug' => false,
        ]);
        $container = $module->getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertTrue($container->has('serializer'));

        $event = $container->get('serializer');

        $this->assertInstanceOf(Serializer::class, $event);

        // reload the normalization module from cache

        $module = new NormalizeModule([
            'cache' => '/tmp/Cache.php',
            'debug' => false,
        ]);
        $container = $module->getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertTrue($container->has('serializer'));

        $event = $container->get('serializer');

        $this->assertInstanceOf(Serializer::class, $event);
    }
}

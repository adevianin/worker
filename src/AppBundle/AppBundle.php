<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OldSound\RabbitMqBundle\DependencyInjection\OldSoundRabbitMqExtension;
use OldSound\RabbitMqBundle\DependencyInjection\Compiler\RegisterPartsPass;

class AppBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerExtension(new OldSoundRabbitMqExtension());
        $container->addCompilerPass(new RegisterPartsPass());
    }
}

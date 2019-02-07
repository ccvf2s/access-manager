<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Application\Permission\Parser;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class SecurityAccessConfiguration implements ConfigurationInterface
{

  /**
   * Generates the configuration tree builder.
   *
   * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
   */
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder();
    $rootNode = $treeBuilder->root('ACCESS');

    $rootNode
        ->children()
          ->arrayNode('ROLES')
            ->useAttributeAsKey('id')
            ->prototype('array')
              ->performNoDeepMerging()
              ->beforeNormalization()->ifString()->then(function ($v) { return ['value' => $v]; })->end()
              ->prototype('scalar')->end()
            ->end()
          ->end()
        ->end();

    return $treeBuilder;
  }

}
<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Ccvf2s\AccessManager\Application\Permission\Parser;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class YamlParser
{

  /**
   * @var Processor
   */
  private $processor;

  public function __construct()
  {
    $this->processor = new Processor();
  }

  /**
   * @param string $filePath
   *
   * @return array
   */
  public function parseFile(string $filePath) : array
  {
    $configs = Yaml::parseFile($filePath);
    return $this->processor->processConfiguration(new SecurityAccessConfiguration(), $configs);
  }

}
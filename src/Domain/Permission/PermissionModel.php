<?php
declare(strict_types = 1);

/**
 * @author    Dominick Makome <dmakome@wayfair.com>
 * @copyright 2019 Wayfair LLC - All rights reserved
 */

namespace Ccvf2s\AccessManager\Domain\Permission;

class PermissionModel
{

  /**
   * @var string
   */
  private $name;

  /**
   * PermissionModel constructor.
   *
   * @param string $name
   */
  public function __construct(string $name)
  {
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getName() : string {
    return $this->name;
  }

}
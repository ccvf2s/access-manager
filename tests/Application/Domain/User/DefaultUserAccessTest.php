<?php
declare(strict_types = 1);

/**
 * @author    Dominick Makome <makomedominick@gmail.com>
 */

namespace Tests\Ccvf2s\AccessManager\Application\Domain\User;

use Ccvf2s\AccessManager\Domain\Permission\PermissionModel;
use Ccvf2s\AccessManager\Domain\User\UserModel;
use Ccvf2s\AccessManager\Application\User\DefaultUserAccess;
use PHPUnit\Framework\TestCase;

class DefaultUserAccessTest extends TestCase
{

  /**
   * @dataProvider dataProvider
   *
   * @param UserModel $userModel
   * @param array     $expectedPermission
   * @param bool      $expectedAccess
   */
  public function testCheck(UserModel $userModel, array $expectedPermission, bool $expectedAccess)
  {
    $defaultUserAccess = new DefaultUserAccess();
    $this->assertEquals($expectedAccess, $defaultUserAccess->check($userModel, $expectedPermission));
  }

  /**
   * @return array
   */
  public function dataProvider() : array
  {
    $permission1 = $this->prophesize(PermissionModel::class);
    $permission1
        ->getName()
        ->shouldBeCalled()
        ->willReturn('ALLOWED_1');

    $permission2 = $this->prophesize(PermissionModel::class);
    $permission2
        ->getName()
        ->shouldBeCalled()
        ->willReturn('ALLOWED_2');

    $permission3 = $this->prophesize(PermissionModel::class);
    $permission3
        ->getName()
        ->shouldBeCalled()
        ->willReturn('ALLOWED_3');

    $permission4 = $this->prophesize(PermissionModel::class);
    $permission4
        ->getName()
        ->shouldBeCalled()
        ->willReturn('ALLOWED_4');


    $expectedPermissions = [$permission1->reveal(), $permission2->reveal(), $permission3->reveal()];

    $user1 = $this->prophesize(UserModel::class);
    $user1
        ->getPermissions()
        ->shouldBeCalled()
        ->willReturn(array_merge($expectedPermissions, [$permission4->reveal()]));

    $user2 = $this->prophesize(UserModel::class);
    $user2
        ->getPermissions()
        ->shouldBeCalled()
        ->willReturn($expectedPermissions);

    $user3 = $this->prophesize(UserModel::class);
    $user3
        ->getPermissions()
        ->shouldBeCalled()
        ->willReturn([$permission1->reveal(), $permission2->reveal()]);

    $user4 = $this->prophesize(UserModel::class);
    $user4
        ->getPermissions()
        ->shouldBeCalled()
        ->willReturn([$permission1->reveal()]);

    return [
        'User with more than three permissions' => [
            'user'                 => $user1->reveal(),
            'expected permissions' => $expectedPermissions,
            'expected access'      => true,

        ],
        'User with same permissions'            => [
            'user'                 => $user2->reveal(),
            'expected permissions' => $expectedPermissions,
            'expected access'      => true,

        ],
        'User with only two permissions'        => [
            'user'                 => $user3->reveal(),
            'expected permissions' => $expectedPermissions,
            'expected access'      => false,
        ],
        'User with one permission'              => [
            'user'                 => $user4->reveal(),
            'expected permissions' => $expectedPermissions,
            'expected access'      => false,
        ],
    ];
  }
}
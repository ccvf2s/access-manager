<?php
declare(strict_types = 1);

/**
 * @author Dominick Makome <makomedominick@gmail.com>
 */

namespace Tests\Ccvf2s\AccessManager\Application;

use Ccvf2s\AccessManager\Application\DefaultDecisionManager;
use Ccvf2s\AccessManager\Application\Permission\Parser\YamlParser;
use Ccvf2s\AccessManager\Application\Permission\YamlPermissionProvider;
use Ccvf2s\AccessManager\Application\User\DefaultUserAccess;
use Ccvf2s\AccessManager\Domain\Permission\PermissionModel;
use Ccvf2s\AccessManager\Domain\User\UserModel;
use Ccvf2s\AccessManager\Domain\User\UserProvider;
use PHPUnit\Framework\TestCase;

class DefaultDecisionManagerTest extends TestCase
{

  /**
   * @dataProvider dataProvider
   *
   * @param string    $role
   * @param UserModel $user
   * @param bool      $expectedAccess
   */
  public function testIsGranted(string $role, UserModel $user, bool $expectedAccess)
  {
    $id           = 'ID';
    $filePath     = __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'security.yml';

    $userProvider = $this->prophesize(UserProvider::class);
    $userProvider
        ->findUser($id)
        ->shouldBeCalled()
        ->willReturn($user);

    $decisionManager = new DefaultDecisionManager(
        new YamlPermissionProvider(new YamlParser(), $filePath),
        $userProvider->reveal(),
        new DefaultUserAccess()
    );

    $this->assertEquals($expectedAccess, $decisionManager->isGranted($role, $id));
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
            'role'            => 'ROLE_ADMIN',
            'user'            => $user1->reveal(),
            'expected access' => true,

        ],
        'User with same permissions'            => [
            'role'            => 'ROLE_ADMIN',
            'user'            => $user2->reveal(),
            'expected access' => true,

        ],
        'User with only two permissions'        => [
            'role'            => 'ROLE_ADMIN',
            'user'            => $user3->reveal(),
            'expected access' => false,
        ],
        'User with one permission'              => [
            'role'            => 'ROLE_ADMIN',
            'user'            => $user4->reveal(),
            'expected access' => false,
        ],
    ];
  }

}
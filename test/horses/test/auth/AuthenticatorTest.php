<?php

namespace horses\test\config;

use horses\auth\AccessControlException;
use horses\auth\UserId;
use horses\test\AbstractTest;
use horses\auth\Authenticator;
use horses\action\AuthenticatedAction;
use horses\action\AuthenticatingAction;
use horses\action\StatefulAction;

interface AuthingAction extends AuthenticatedAction, AuthenticatingAction {}
interface AuthingSfulAction extends AuthenticatedAction, AuthenticatingAction, StatefulAction {}
interface SfulAction extends AuthenticatedAction, StatefulAction {}

class AuthenticatorTest extends AbstractTest
{
    /** @var Authenticator */
    protected $authenticator;
    
    
    protected function setUp()
    {
        parent::setUp();
        $this->authenticator = new Authenticator();
    }

    public function testNoAuthNoUser()
    {
        $user = false;
        $action = $this->getBasicMock('\horses\action\AuthenticatedAction');
        $action->expects($this->any())
            ->method('setAuthentication')
            ->will($this->returnCallback(function($createdUser) use (&$user) {$user = $createdUser;}));
        $action->expects($this->any())
            ->method('getAccessPolicy')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessPolicy')));

        $request = $this->getBasicMock('\horses\Request');

        $this->authenticator->authenticate($request, $action);
        $this->assertNull($user);
    }

    public function testAuthenticating()
    {
        $user = false;

        $userIdFactory = $this->getBasicMock('\horses\auth\UserIdFactory');
        $userIdFactory->expects($this->any())
            ->method('getUserId')
            ->will($this->returnValue(new UserId('foo')));

        $instantiatedUser = $this->getBasicMock('\horses\auth\User');
        $instantiatedUser->expects($this->any())
            ->method('getAccessGrants')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessGrants')));
        $userFactory = $this->getBasicMock('\horses\auth\UserFactory');
        $userFactory->expects($this->any())
            ->method('getUserFromId')
            ->will($this->returnValue($instantiatedUser));

        $credentialsFactory = $this->getBasicMock('\horses\auth\CredentialsFactory');
        $credentialsFactory->expects($this->any())
            ->method('getCredentials')
            ->will($this->returnValue(null));

        $action = $this->getBasicMock('\horses\test\config\AuthingAction');
        $action->expects($this->any())
            ->method('setAuthentication')
            ->will($this->returnCallback(function($createdUser) use (&$user) {$user = $createdUser;}));
        $action->expects($this->any())
            ->method('getUserIdFactory')
            ->will($this->returnValue($userIdFactory));
        $action->expects($this->any())
            ->method('getUserFactory')
            ->will($this->returnValue($userFactory));
        $action->expects($this->any())
            ->method('getCredentialsFactory')
            ->will($this->returnValue($credentialsFactory));
        $action->expects($this->any())
            ->method('getAccessPolicy')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessPolicy')));

        $this->authenticator->authenticate($this->getBasicMock('\horses\Request'), $action);
        $this->assertEquals($instantiatedUser, $user);
    }

    public function testAuthenticatingNoUser()
    {
        $user = false;

        $userIdFactory = $this->getBasicMock('\horses\auth\UserIdFactory');
        $userIdFactory->expects($this->any())
            ->method('getUserId')
            ->will($this->returnValue(null));

        $userFactory = $this->getBasicMock('\horses\auth\UserFactory');
        $userFactory->expects($this->any())
            ->method('getUserFromId')
            ->will($this->returnValue(null));

        $credentialsFactory = $this->getBasicMock('\horses\auth\CredentialsFactory');
        $credentialsFactory->expects($this->any())
            ->method('getCredentials')
            ->will($this->returnValue(null));

        $action = $this->getBasicMock('\horses\test\config\AuthingAction');
        $action->expects($this->any())
            ->method('setAuthentication')
            ->will($this->returnCallback(function($createdUser) use (&$user) {$user = $createdUser;}));
        $action->expects($this->any())
            ->method('getUserIdFactory')
            ->will($this->returnValue($userIdFactory));
        $action->expects($this->any())
            ->method('getUserFactory')
            ->will($this->returnValue($userFactory));
        $action->expects($this->any())
            ->method('getCredentialsFactory')
            ->will($this->returnValue($credentialsFactory));
        $action->expects($this->any())
            ->method('getAccessPolicy')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessPolicy')));

        $this->authenticator->authenticate($this->getBasicMock('\horses\Request'), $action);
        $this->assertNull($user);
    }

    public function testAuthenticatingCredentials()
    {
        $user = false;

        $userIdFactory = $this->getBasicMock('\horses\auth\UserIdFactory');
        $userIdFactory->expects($this->any())
            ->method('getUserId')
            ->will($this->returnValue(new UserId('foo')));

        $instantiatedUser = $this->getBasicMock('\horses\auth\User');
        $instantiatedUser->expects($this->any())
            ->method('getAccessGrants')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessGrants')));
        $userFactory = $this->getBasicMock('\horses\auth\UserFactory');
        $userFactory->expects($this->any())
            ->method('getUserFromId')
            ->will($this->returnValue($instantiatedUser));

        $credentialsFactory = $this->getBasicMock('\horses\auth\CredentialsFactory');
        $credentialsFactory->expects($this->any())
            ->method('getCredentials')
            ->will($this->returnValue(null));

        $action = $this->getBasicMock('\horses\test\config\AuthingAction');
        $action->expects($this->any())
            ->method('setAuthentication')
            ->will($this->returnCallback(function($createdUser) use (&$user) {$user = $createdUser;}));
        $action->expects($this->any())
            ->method('getUserIdFactory')
            ->will($this->returnValue($userIdFactory));
        $action->expects($this->any())
            ->method('getUserFactory')
            ->will($this->returnValue($userFactory));
        $action->expects($this->any())
            ->method('getCredentialsFactory')
            ->will($this->returnValue($credentialsFactory));
        $action->expects($this->any())
            ->method('getAuthorizationNeeded')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\Authorization')));
        $action->expects($this->any())
            ->method('getAccessPolicy')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessPolicy')));

        $this->authenticator->authenticate($this->getBasicMock('\horses\Request'), $action);
        $this->assertEquals($instantiatedUser, $user);
    }

    /**
     * @expectedException \horses\auth\AccessControlException
     */
    public function testAuthenticatingCredentialsUnauthorized()
    {
        $user = false;

        $userIdFactory = $this->getBasicMock('\horses\auth\UserIdFactory');
        $userIdFactory->expects($this->any())
            ->method('getUserId')
            ->will($this->returnValue(new UserId('foo')));

        $instantiatedUser = $this->getBasicMock('\horses\auth\User');
        $instantiatedUser->expects($this->any())
            ->method('getAccessGrants')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessGrants')));
        $userFactory = $this->getBasicMock('\horses\auth\UserFactory');
        $userFactory->expects($this->any())
            ->method('getUserFromId')
            ->will($this->returnValue($instantiatedUser));

        $credentialsFactory = $this->getBasicMock('\horses\auth\CredentialsFactory');
        $credentialsFactory->expects($this->any())
            ->method('getCredentials')
            ->will($this->returnValue(null));

        $action = $this->getBasicMock('\horses\test\config\AuthingAction');
        $action->expects($this->any())
            ->method('setAuthentication')
            ->will($this->returnCallback(function($createdUser) use (&$user) {$user = $createdUser;}));
        $action->expects($this->any())
            ->method('getUserIdFactory')
            ->will($this->returnValue($userIdFactory));
        $action->expects($this->any())
            ->method('getUserFactory')
            ->will($this->returnValue($userFactory));
        $action->expects($this->any())
            ->method('getCredentialsFactory')
            ->will($this->returnValue($credentialsFactory));
        $action->expects($this->any())
            ->method('getAuthorizationNeeded')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\Authorization')));
        $accessPolicy = $this->getBasicMock('\horses\auth\AccessPolicy');
        $accessPolicy->expects($this->any())
            ->method('authorize')
            ->will($this->throwException(new AccessControlException()));
        $action->expects($this->any())
            ->method('getAccessPolicy')
            ->will($this->returnValue($accessPolicy));

        $this->authenticator->authenticate($this->getBasicMock('\horses\Request'), $action);
        $this->assertEquals($instantiatedUser, $user);
    }

    public function testStateful()
    {
        $user = false;

        $state = $this->getBasicMock('\horses\State');
        $state->expects($this->any())
            ->method('getUserId')
            ->will($this->returnValue(new UserId('foo')));

        $instantiatedUser = $this->getBasicMock('\horses\auth\User');
        $instantiatedUser->expects($this->any())
            ->method('getAccessGrants')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessGrants')));
        $userFactory = $this->getBasicMock('\horses\auth\UserFactory');
        $userFactory->expects($this->any())
            ->method('getUserFromId')
            ->will($this->returnValue($instantiatedUser));

        $action = $this->getBasicMock('\horses\test\config\SfulAction');
        $action->expects($this->any())
            ->method('setAuthentication')
            ->will($this->returnCallback(function($createdUser) use (&$user) {$user = $createdUser;}));
        $action->expects($this->any())
            ->method('getState')
            ->will($this->returnValue($state));
        $action->expects($this->any())
            ->method('getUserFactory')
            ->will($this->returnValue($userFactory));
        $action->expects($this->any())
            ->method('getAccessPolicy')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessPolicy')));

        $this->authenticator->authenticate($this->getBasicMock('\horses\Request'), $action);
        $this->assertEquals($instantiatedUser, $user);
    }

    public function testStatefulAuthing()
    {
        $user = false;
        $createdUserId = false;

        $state = $this->getBasicMock('\horses\State');
        $state->expects($this->any())
            ->method('getUserId')
            ->will($this->returnValue(null));
        $state->expects($this->any())
            ->method('saveUserId')
            ->will($this->returnCallback(function($userId) use (&$createdUserId) {$createdUserId = $userId;}));

        $userIdFactory = $this->getBasicMock('\horses\auth\UserIdFactory');
        $userIdFactory->expects($this->any())
            ->method('getUserId')
            ->will($this->returnValue(new UserId('foo')));

        $instantiatedUser = $this->getBasicMock('\horses\auth\User');
        $instantiatedUser->expects($this->any())
            ->method('getAccessGrants')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessGrants')));
        $userFactory = $this->getBasicMock('\horses\auth\UserFactory');
        $userFactory->expects($this->any())
            ->method('getUserFromId')
            ->will($this->returnValue($instantiatedUser));

        $credentialsFactory = $this->getBasicMock('\horses\auth\CredentialsFactory');
        $credentialsFactory->expects($this->any())
            ->method('getCredentials')
            ->will($this->returnValue(null));

        $action = $this->getBasicMock('\horses\test\config\AuthingSfulAction');
        $action->expects($this->any())
            ->method('setAuthentication')
            ->will($this->returnCallback(function($createdUser) use (&$user) {$user = $createdUser;}));
        $action->expects($this->any())
            ->method('getUserIdFactory')
            ->will($this->returnValue($userIdFactory));
        $action->expects($this->any())
            ->method('getUserFactory')
            ->will($this->returnValue($userFactory));
        $action->expects($this->any())
            ->method('getState')
            ->will($this->returnValue($state));
        $action->expects($this->any())
            ->method('getCredentialsFactory')
            ->will($this->returnValue($credentialsFactory));
        $action->expects($this->any())
            ->method('getAccessPolicy')
            ->will($this->returnValue($this->getBasicMock('\horses\auth\AccessPolicy')));

        $this->authenticator->authenticate($this->getBasicMock('\horses\Request'), $action);
        $this->assertEquals($instantiatedUser, $user);
        $this->assertEquals(new UserId('foo'), $createdUserId);
    }
}
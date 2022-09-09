<?php

namespace App\Security;

use App\Libs\Mappers\UserRecord;
use kalanis\kw_mapper\MapperException;
use kalanis\kw_mapper\Search\Search;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    /**
     * @param string|int $identifier
     * @return UserInterface
     * @throws MapperException
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier($identifier): UserInterface
    {
        return $this->userLookup(strval($identifier));
    }

    /**
     * @deprecated since Symfony 5.3, loadUserByIdentifier() is used instead
     */
    public function loadUserByUsername($username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @param UserInterface $user
     * @throws MapperException
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserRecord) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->userLookup(strval($user->getUserIdentifier()));
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return UserRecord::class === $class || is_subclass_of($class, UserRecord::class);
    }

    /**
     * Upgrades the hashed password of a user, typically for using a better hash algorithm.
     */
    /**
     * @param PasswordAuthenticatedUserInterface $user
     * @param string $newHashedPassword
     * @throws MapperException
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if ($this->supportsClass(get_class($user))) {
            /** @var UserRecord $user */
            $user->setPassword($newHashedPassword);
            $user->save();
        }
    }

    /**
     * @param string $identifier
     * @throws MapperException
     * @return UserRecord
     */
    protected function userLookup(string $identifier)
    {
        $search = new Search(new UserRecord());
        $search->exact('id', strval($identifier));
        $search->null('deleted');
        if (1 > $search->getCount()) {
            throw new UserNotFoundException();
        }
        $records = $search->getResults();
        return reset($records);
    }
}

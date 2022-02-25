<?php

namespace Domain\Users;

use Domain\Entity\School;
use Domain\Users\Repository\UserRepositoryInterface;

abstract class User
{
    public const TEACHER_AFFILIATION_VALUES = [
        'member',
        'employee',
//        'faculty'
    ];

    public const STUDENT_AFFILIATION_VALUES = [
        'student',
        'member'
    ];

    public const TEACHER_ROLE = 'TEACHER';
    public const STUDENT_ROLE = 'STUDENT';
    public const CONTENT_MANAGER_ROLE = 'CONTENT-MANAGER';

    protected ?int $id;
    protected ?string $email;
    protected string $name;
    protected ?string $lastLogin;

    // protected UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->id = null;
        $this->lastLogin = null;
        // $this->userRepository = resolve(UserRepositoryInterface::class);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getFormattedName()
    {
        return str_replace(' ',', ', ucwords($this->name));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    // public function getUserRepository(): UserRepositoryInterface
    // {
    //     return $this->userRepository;
    // }

    public function setUserRepository($userRepository): User
    {
        $this->userRepository = $userRepository;

        return $this;
    }

    public function setLastLogin(string $lastLogin): User
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getLastLogin(): ?string
    {
        return $this->lastLogin;
    }

    public function getSchools(){
        return $this->userRepository->getSchools($this);
    }

    ///
    /// ALARM This is a little bit buggy method, because it logs out current user, but not one which is in $this
    ///
    public function logout(): void
    {
        $this->userRepository->logoutFromDevice($this);
    }

    abstract public function hasTeachingPath(int $teachingPathId): bool;

    abstract public function hasAssignment(int $assignmentId): bool;
}

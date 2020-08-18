<?php

class School
{
    protected array $person;
    protected array $classes;

    public function addPerson(Person $person)
    {
        $this->person[] = $person;
    }

    public function addClass(SchoolClass $schoolClass)
    {
        $this->classes[] = $schoolClass;
    }

    public function personsInSchoolCount(): int
    {
        return count($this->person);
    }
}

class SchoolClass
{
    protected array $pupils;
    private Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function addPupil(Pupil $pupil): void
    {
        $this->pupils[] = $pupil;
    }

    public function teacherExp()
    {
        $this->teacher->getExperience();
    }
}

abstract class Person
{
    public string $name;
    protected int $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

class Pupil extends Person
{
    public function isAdult(): bool
    {
        return $this->getAge() >= 18;
    }
}

class Teacher extends Person
{
    private $experience;

    public function __construct(string $name, int $age, $experience)
    {
        parent::__construct($name, $age);
        $this->experience = $experience;
    }

    public function getExperience()
    {
        return $this->getExperience();
    }
}

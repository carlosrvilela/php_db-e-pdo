<?php
namespace  Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;

interface StudentRepository
{
    public function getAllStudents(): array;
    public function getStudentsWithPhones(): array;
    public function getStudentsBirthAt(\DateTimeInterface $birthDate): array;
    public function save(Student $student): Bool;
    public function remove(Student $student): bool;
}
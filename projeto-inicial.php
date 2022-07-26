<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$student = new Student(
    null,
    'Fulano de Tal',
    new \DateTimeImmutable('1999-09-19')
);

echo $student->age();

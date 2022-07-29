<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';
$connection = ConnectionCreator::createConnection();
$studentRepository = new PdoStudentRepository($connection);

try{
    $connection->beginTransaction();

    $student1= new Student(null, 'Estudante 1', new DateTimeImmutable('1111-11-11'));
    $studentRepository->save($student1);

    $student2= new Student(null, 'Estudante 2', new DateTimeImmutable('2222-02-22'));
    $studentRepository->save($student2);

    $connection->commit();
} catch(\PDOException $error){
    print($error->getMessage());
    $connection->rollBack();
}

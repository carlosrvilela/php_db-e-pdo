<?php
require_once 'vendor/autoload.php';

use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

$connection=ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($connection);

function testaGetAll($repository)
{
    $allStudents = $repository->getAllStudents();
    var_dump($allStudents);
}

testaGetAll($repository);

function testaGetStudentsBirthAt($repository){
    $brithDate = new \DateTimeImmutable('1999-09-19');
    $result = $repository->getStudentsBirthAt($brithDate);
    var_dump($result);
}

testaGetStudentsBirthAt($repository);

function testaSvareRemove($repository){
    $student = new Student(
        null,
        "Novo Estudante Novo",
        new \DateTimeImmutable('1888-08-18')
    );

    //var_dump($repository->save($student));
    var_dump($repository->remove($student));
}

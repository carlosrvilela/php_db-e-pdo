<?php
require_once 'vendor/autoload.php';
use Alura\Pdo\Domain\Model\Student;

$pdo = \Alura\Pdo\Infrastructure\Persistence\ConnectionCreator::createConnection();
$student = new Student(
    null,
    "Fulano de Tal",
    new \DateTimeImmutable('1999-09-19')
);

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birthDate);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(':name', $student->name());
$statement->bindValue(':birthDate', $student->birthDate()->format('Y-m-d'));
if ($statement->execute()){
    print "Foi!";
}else{
    print "erro!";
}
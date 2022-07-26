<?php
require_once 'vendor/autoload.php';

use Alura\Pdo\Domain\Model\Student;


$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:'.$dbPath);

$statement = $pdo->query('SELECT * FROM students WHERE id = 1;');
var_dump($statement->fetchColumn(1));
exit();
$statement = $pdo->query('SELECT * FROM students;');

while ($studentData = $statement->fetch(PDO::FETCH_ASSOC)) {
    $student = new Student(
        $studentData['id'],
        $studentData['name'],
        new \DateTimeImmutable($studentData['birth_date'])
    );

    echo $student->age() . PHP_EOL;
}

exit();
$studentsDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
var_dump($studentsDataList);

$studentsList=[];
foreach ($studentsDataList as $studentData){
    $studentsList = new Student($studentData['id'], $studentData['name'], new \DateTimeImmutable($studentData['birth_date']));
}

var_dump($studentsList);
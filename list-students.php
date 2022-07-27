<?php
require_once 'vendor/autoload.php';

use Alura\Pdo\Domain\Model\Student;


$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:'.$dbPath);

$birthDate = new \DateTimeImmutable('1999-09-19');
print $birthDate->format('Y-m-d')."\n";


$sqlSearch = 'SELECT * FROM students WHERE birth_date = :birth_date;';
$statement = $pdo->prepare($sqlSearch);
$statement->bindValue(':birth_date', $birthDate->format('Y-m-d'));
var_dump($statement);
$statement->execute();
var_dump($statement->fetchAll(PDO::FETCH_ASSOC));



// $sqlSearch = "SELECT * FROM students; WHERE birth_date = ".$birthDate->format('Y-m-d');
// $statement = $pdo->query($sqlSearch);

// $sqlSearch = "SELECT * FROM students; WHERE birth_date = ?";
// $statement = $pdo->prepare($sqlSearch);
// $statement->bindValue(param:1, value:$birthDate->format('Y-m-d'));
// var_dump($statement);
// var_dump($statement->execute());
// var_dump($statement->fetchAll(PDO::FETCH_ASSOC));

// $sqlSearch = "SELECT * FROM students; WHERE birth_date = :birth_date";
// $statement = $pdo->prepare($sqlSearch);
// $statement->bindValue(':birth_date', $birthDate->format('Y-m-d'));
// var_dump($statement);
// var_dump($statement->execute());



exit();

// $statement = $pdo->query('SELECT * FROM students WHERE id = 1;');
// var_dump($statement->fetchColumn(1));
// exit();
// $statement = $pdo->query('SELECT * FROM students;');

// while ($studentData = $statement->fetch(PDO::FETCH_ASSOC)) {
//     $student = new Student(
//         $studentData['id'],
//         $studentData['name'],
//         new \DateTimeImmutable($studentData['birth_date'])
//     );

//     echo $student->age() . PHP_EOL;
// }

// exit();
$statement = $pdo->query('SELECT * FROM students;');
$studentsDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
//var_dump($studentsDataList);

$studentsList=[];
foreach ($studentsDataList as $studentData){
    print $studentData['name']."\n";
    $studentsList[] = new Student($studentData['id'], $studentData['name'], new \DateTimeImmutable($studentData['birth_date']));
}

var_dump($studentsList);
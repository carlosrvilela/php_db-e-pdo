<?php
require_once 'vendor/autoload.php';

use Alura\Pdo\Domain\Model\Student;


$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:'.$dbPath);

$sqlDelete = 'DELETE FROM students WHERE id = :id';
$pepareStatement = $pdo->prepare($sqlDelete);
$pepareStatement->bindValue(':id', 6, PDO::PARAM_INT);

if ($pepareStatement->execute()){
    print "Foi!";
}else{
    print "erro!";
}
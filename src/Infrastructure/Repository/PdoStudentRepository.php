<?php
namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Domain\Model\Student;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    private function hydrateStudentList(\PDOStatement $statement): array
    {
        $studentsDataList = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($studentsDataList as $studentData){
            $studentsList[] = new Student($studentData['id'], $studentData['name'], new \DateTimeImmutable($studentData['birth_date']));
        }

        return $studentsList;
    }
    public function getAllStudents(): array
    {
        $statement = $this->connection->query('SELECT * FROM students;');
        return $this->hydrateStudentList($statement);
        
    }

    public function getStudentsBirthAt(\DateTimeInterface $birthDate): array
    {
        $sqlSearch = 'SELECT * FROM students WHERE birth_date = :birth_date;';
        $statement = $this->connection->prepare($sqlSearch);
        $statement->bindValue(':birth_date', $birthDate->format('Y-m-d'));
        $statement->execute();

        return $this->hydrateStudentList($statement);
    }

    public function save(Student $student): Bool
    {

        if($student->id() == null){
            return $this->insert($student);
        }
        print("Esudante existente\n");
        return $this->update($student);
    }

    public function insert(Student $student): Bool
    {
        $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birthDate);";
        $statement = $this->connection->prepare($sqlInsert);
        $statement->bindValue(':name', $student->name());
        $statement->bindValue(':birthDate', $student->birthDate()->format('Y-m-d'));
        $sucess = $statement->execute();

        if ($sucess) {
            $student->defineId($this->connection->lastInsertId());
        }   

        return $sucess;
    }

    public function update(Student $student): Bool
    {
        
        $sqlUpdate = 'UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id;';
        $statement = $this->connection->prepare($sqlUpdate);
        $statement->bindValue(':name', $student->name());
        $statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(Student $student): bool
    {
        $sqlDelete = 'DELETE FROM students WHERE id = :id';
        $statement = $this->connection->prepare($sqlDelete);
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);
        
        return $statement->execute();
    }
}
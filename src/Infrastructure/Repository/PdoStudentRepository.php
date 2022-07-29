<?php
namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Domain\Model\Student;
use PDO;
use SNMP;

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
        $studentsList = [];


        foreach ($studentsDataList as $studentData){
            $studentsList[] = new Student(
                $studentData['id'], 
                $studentData['name'], 
                new \DateTimeImmutable($studentData['birth_date'])
            );
        }

        return $studentsList;
    }

    private function fillPhonesOf(Student $student): void
    {
        $sqlQuery = "SELECT id, area_code, number FROM phones WHERE student_id = :id";
        $statement =  $this->connection->prepare($sqlQuery);
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);
        $statement->execute();

        $phoneDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($phoneDataList as $phoneData) {
            $phone = new Phone(
                $phoneData['id'],
                $phoneData['area_code'],
                $phoneData['number']
            );

            $student->addPhone($phone);
        }

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

    public function getStudentsWithPhones():array
    {
        $studentsWhitPhones = [];

        $sqlQuery = 'SELECT students.id,
                            students.name,
                            students.birth_date,
                            phones.id AS phone_id,
                            phones.area_code,
                            phones.number
                    FROM students
                    JOIN phones ON students.id = phones.student_id;';

        $statement = $this->connection->query($sqlQuery);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            if (!array_key_exists($row['id'], $studentsWhitPhones)) {
                $studentsWhitPhones[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                    new \DateTimeImmutable($row['birth_date'])
                );
            }
            $phone = new Phone(
                    $row['phone_id'], 
                    $row['area_code'],
                    $row['number']
                );
            $studentsWhitPhones[$row['id']]->addPhone($phone);
        }

        return $studentsWhitPhones;
    }
}
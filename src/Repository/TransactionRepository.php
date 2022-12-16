<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function save(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    // public function findAllGreaterThanPrice(int $price): array
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = '
    //         SELECT * FROM product p
    //         WHERE p.price > :price
    //         ORDER BY p.price ASC
    //         ';
    //     $stmt = $conn->prepare($sql);
    //     $resultSet = $stmt->executeQuery(['price' => $price]);

    //     // returns an array of arrays (i.e. a raw data set)
    //     return $resultSet->fetchAllAssociative();
    // }

    public function findCompte(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT compte.id
        FROM compte
        INNER JOIN user ON compte.id_user_id=user.id';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
    
        return $resultSet->fetchAllAssociative();

    }


//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    // public function findByRole(string $role)
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.roles LIKE :role')
    //         ->setParameter('role', "%\"$role\"%")
    //         ->getQuery()
    //         ->getResult()
    //         ;
    // }
}

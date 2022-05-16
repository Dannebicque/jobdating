<?php

namespace App\Repository;

use App\Entity\Diplome;
use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Offre $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Offre $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByEntreprise(?UserInterface $user)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.entreprise', 'e')
            ->innerJoin('e.representants', 'r')
            ->where('r.id = :user')
            ->setParameter('user', $user->getId())
            ->orderBy('o.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDiplome(Diplome $diplome)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.entreprise', 'e')
            ->innerJoin('o.parcours', 'p')
            ->where('p.diplome = :diplome')
            ->setParameter('diplome', $diplome->getId())
            ->orderBy('e.raison_sociale', 'ASC')
            ->addOrderBy('o.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

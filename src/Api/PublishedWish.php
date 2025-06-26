<?php
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Wish;
namespace App\Api;



class PublishedWishExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(QueryBuilder $qb, QueryNameGeneratorInterface $qng, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ($resourceClass === Wish::class) {
            $qb->andWhere('o.isPublished = true');
        }
    }

    public function applyToItem(QueryBuilder $qb, QueryNameGeneratorInterface $qng, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        if ($resourceClass === Wish::class) {
            $qb->andWhere('o.isPublished = true');
        }
    }

}

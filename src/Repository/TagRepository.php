<?php

declare(strict_types=1);

namespace App\Repository;

class TagRepository extends AbstractRepository
{
    public function findByPostId(int $postId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $statement = $queryBuilder
            ->select('*')
            ->from('tag')
            ->innerJoin('tag', 'post_tag', 'relation', 'relation.tag_id = tag.id')
            ->where(
                $queryBuilder->expr()->eq('relation.post_id', ':postId')
            )
            ->setParameter('postId', $postId)
            ->execute();

        return $statement instanceof \PDOStatement ? $statement->fetchAll() : [];
    }
}

<?php

namespace App\Repository;

use App\Contract\CatalogInterface;
use PDO;

/**
 * Handles catalog database operations using PDO
 * and communicates with stored procedures.
 */
class CatalogRepository extends BaseRepository implements CatalogInterface
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'view_catalog', 'media_id');
    }

    public function getRandomCatalog()
    {
        $result = $this->db->query('SELECT * FROM view_random');

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
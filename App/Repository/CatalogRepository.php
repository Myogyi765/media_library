<?php

namespace App\Repository;

use App\Contract\CatalogInterface;
use App\Model\Catalog;
use PDO;

class CatalogRepository extends BaseRepository implements CatalogInterface
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'view_catalog', 'media_id');
    }

    public function getRandomCatalog(): array
    {
        $result = $this->db->query('SELECT * FROM view_random');

        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

     //   return array_map(
       //     fn ($row) => Catalog::fromArray($row),
         //   $rows
        //);

     
        return $rows;
    }

    public function getSingleItem(int $id): ?array
    {
        $stmt = $this->execute(
            "CALL sp_get_item_full_detail(?)",
            [$id]
        );

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            return null;
        }

        // keep relation mapping inside repository (DB concern)
        $stmt->nextRowset();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $role = strtolower($row['role']);

            // safer initialization (avoid undefined index warnings)
            if (!isset($item[$role])) {
                $item[$role] = [];
            }

            $item[$role][] = $row['fullname'];
        }

        return $item;
    }


    
}
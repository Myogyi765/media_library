<?php

namespace App\Model;

class Catalog
{
     private array $data;

    public function __construct(
         private mixed $media_id,
         private ?string $title,
         private ?string $discription,
         array $data = []
    ){
        $this->data = $data;
    }
    /*
     * ==========================================
     * OPTIONAL: CONVERT OBJECT TO ARRAY
     * ==========================================
     */
    public function toArray(): array
    {
        return [
            'media_id' => $this->media_id,
            'title' => $this->title,
            'discription' => $this->discription,
           
        ];
    }

    /*
     * ==========================================
     * GETTERS
     * ==========================================
     */

    public function getId(): int
    {
        return $this->media_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDiscription(): string
    {
        return $this->discription;
    }

  
}
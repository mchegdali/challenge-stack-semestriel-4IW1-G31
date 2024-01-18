<?php

namespace App\Entity\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait Blameable
{
    #[ORM\ManyToOne(User::class)]
    #[Gedmo\Blameable(on: 'create')]
    private \DateTime $createdBy;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Gedmo\Blameable(on: 'update')]
    private \DateTime $updatedBy;

    public function getCreatedBy(): \DateTime
    {
        return $this->createdBy;
    }

    public function setCreatedBy(\DateTime $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getUpdatedBy(): \DateTime
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(\DateTime $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}

<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MigrationVersion.
 *
 * @ORM\Table(name="migration_version")
 * @ORM\Entity
 */
class MigrationVersion
{
    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="migration_version_version_seq", allocationSize=1, initialValue=1)
     */
    private $version;

    /**
     * Get version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}

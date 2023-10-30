<?php

declare(strict_types=1);

namespace OCA\FilesRating\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string|null getUserId()
 * @method void setUserId(?string $userId)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getContent()
 * @method void setContent(string $content)
 * @method int getLastModified()
 * @method void setLastModified(int $lastModified)
 */
class FilesRating extends Entity implements \JsonSerializable {

    /** @var string */
    protected $userId;
    // /** @var string */
    // protected $name;
    /** @var integer */
    protected $fileId;
	/** @var int */
    protected $rate;
	/** @var boolean */
    protected $isAvg;

    public function __construct() {
        $this->addType('user_id', 'string');
        // $this->addType('name', 'string');
        $this->addType('fileId', 'integer');
        $this->addType('rate', 'integer');
		$this->addType('isAvg', 'boolean');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            // 'name' => $this->name,
			'fileId' => $this->fileId,
			'rate' => $this->rate,
			'isAvg' =? this->isAvg
        ];
    }
}
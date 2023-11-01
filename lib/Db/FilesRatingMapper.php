<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Ano Rangga Rahardika <rahardikaku@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\FilesRating\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @template-extends QBMapper<Note>
 */
class FilesRatingMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'filesrating_rate', FilesRating::class);
	}

	/**
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 * @throws DoesNotExistException
	 */
	public function findByFile(string $fileId): FilesRating {
		/* @var $qb IQueryBuilder */
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->getTableName())
			->where($qb->expr()->eq('fileId', $qb->createNamedParameter($fileId, IQueryBuilder::PARAM_INT)));
		return $this->findEntities($qb);
	}

	/**
	 * selalu return 1 record
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 * @throws DoesNotExistException
	 */
	public function findByUserAndFile(string $userId, string $fileId) {
		/* @var $qb IQueryBuilder */
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('file_id', $qb->createNamedParameter($fileId, IQueryBuilder::PARAM_STR))
			)
			->andWhere(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);
        return $this->findEntity($qb);
	}

	/**
	 * @param string $fileId
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 * @throws DoesNotExistException
	 * find rating average dari file
	 */
	public function findAvgRateFile(string $fileId) {
		$qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('file_id', $qb->createNamedParameter($fileId, IQueryBuilder::PARAM_STR))
			)
			->andWhere(
                $qb->expr()->eq('is_avg', $qb->createNamedParameter(true, IQueryBuilder::PARAM_BOOL))
			);
        return $this->findEntity($qb);
	}

	/**
	 * @param string $fileId
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 * @throws DoesNotExistException
	 * find rating average dari file
	 */
	public function computeAvgRateFile(string $fileId) {
        $qb = $this->db->getQueryBuilder();

        $qb->selectAlias($qb->createFunction('AVG( rate )'), 'avg_rate')
           ->from($this->getTableName())
           ->where(
               $qb->expr()->eq('file_id', $qb->createNamedParameter($fileId, IQueryBuilder::PARAM_STR))
           );

        $cursor = $qb->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();

        return $row['avg_rate'];
    }


	public function computeGroupRate(string $fileId) {
		$qb = $this->db->getQueryBuilder();

        
		$qb->select('rate')
			->selectAlias($qb->createFunction('COUNT( * )'),'file_id')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('file_id', $qb->createNamedParameter($fileId, IQueryBuilder::PARAM_STR))
			)
			->addGroupBy('rate');
			// ->groupBy('rate');
			// ->andWhere(
            //     $qb->expr()->eq('is_avg', $qb->createNamedParameter(false, IQueryBuilder::PARAM_BOOL))
			// )
			// ->groupBy('rate');
		// $cursor = $qb->execute();
        // $row = $cursor->fetch();
        // $cursor->closeCursor();
        // return $row;
		return $this->findEntities($qb);
	}

	/**
	 * @param string $fileId
	 * @throws DoesNotExistException
	 * find semua rate dari file
	 * GROUP BY RATE
	 */
	public function findRateFile(string $fileid) {
		$qb = $this->db->getQueryBuilder();

        $qb->selectAlias($qb->createFunction('AVG( rate )'), 'avg_rate')
           ->from($this->getTableName())
           ->where(
               $qb->expr()->eq('file_id', $qb->createNamedParameter($fileId, IQueryBuilder::PARAM_STR))
           );

        $cursor = $qb->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();

        return $row['avg_rate'];
	}

	/**
     * @param string $userId
     * @param string $name
     * @param string $content
     * @return Note
     * @throws Exception
     */
    public function createRate(string $userId, string $fileId, int $rate): FilesRating {
        $filesrating = new FilesRating();
        $filesrating->setUserId($userId);
        // $note->setName($name);
        $filesrating->setFileId($fileId);
		$filesrating->setRate($rate);
		$filesrating->setIsAvg(false);
        // $timestamp = (new DateTime())->getTimestamp();
        return $this->insert($filesrating);
    }

	/**
     * @param int $id
     * @return FilesRating
     * @throws DoesNotExistException
     * @throws Exception
     * @throws MultipleObjectsReturnedException
     */
    public function getRatingById(int $id): FilesRating {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);
        return $this->findEntity($qb);
    }

	/**
     * @param int $id
     * @param string $userId
     * @return FilesRating|null
     * @throws Exception
     */
    public function updateRate(int $id, ?int $rate = null): ?FilesRating {
        if ($rate === null) {
            return null;
        }
        try {
            $filesrating = $this->getRatingById($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }
        if ($rate !== null) {
            $filesrating->setRate($rate);
        }
    
        return $this->update($filesrating);
    }

	/**
     * @param FilesRating $filesrating
     * @param int $rate
     * @return FilesRating|null
     * @throws Exception
     */
	public function updateRateByEntity(FilesRating $filesrating, int $rate) : ?FilesRating{
        $filesrating->setRate($rate);
        return $this->update($filesrating);
	}

	

}

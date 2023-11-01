<?php
namespace OCA\FilesRating\Controller;

use Exception;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCA\FilesRating\Db\FilesRatingMapper;
// use OCA\NoteBook\Service\NoteService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Throwable;

class RatingController extends OCSController {

    public function __construct(
        string             $appName,
        IRequest           $request,
        private FilesRatingMapper $filesRatingMapper,
        // private NoteService $noteService,
        private ?string    $userId
    ) {
        parent::__construct($appName, $request);
    }

    /**
     * @NoAdminRequired
     * @param int $iddoc
     * @return DataResponse
     */
    public function getFilesRating(int $id): DataResponse {
		$data = ['id' => '1', 'rate_avg' => '5'];
        try {
            return new DataResponse($data);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

	/**
     * @NoAdminRequired
	 * @param string $fileId
     * @return DataResponse
     */
    public function getInitialState(string $fileId): DataResponse {
		//get average rating file by computing
		$avgRateFile = $this->filesRatingMapper->computeAvgRateFile($fileId);
		$avgRateFile = number_format((float)$avgRateFile, 1, '.', '');

		//get all rating by file id
		$allRateFile = $this->filesRatingMapper->computeGroupRate($fileId);
	
		// var_dump($allRateFile);

		//get rating current user and file id
		try {
			$ratingUserFile =  $this->filesRatingMapper->findByUserAndFile($this->userId,$fileId);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            $ratingUserFile = null;
        }
		// var_dump($ratingUserFile=>rate);
		$data = [
			'rate_avg' => $avgRateFile ? $avgRateFile : '0',
			'rate_user' => $ratingUserFile ? $ratingUserFile->getRate(): '0',
			'rate_group' => $allRateFile ? $allRateFile : null
		];
		// var_dump($data);
        try {
            return new DataResponse($data);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @NoAdminRequired
     *
     * @param int $fileid
     * @param int $rate
     * @return DataResponse
     */
    public function addFilesRating(string $fileId,?int $rate = null): DataResponse {
		try {
			$ratingUserFile =  $this->filesRatingMapper->findByUserAndFile($this->userId,$fileId);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            $ratingUserFile = null;
        }
		if ($ratingUserFile){
			//update
			$ratingUserFile = $this->filesRatingMapper->updateRateByEntity($ratingUserFile,$rate);
		} else {
			//create
			$ratingUserFile = $this->filesRatingMapper->createRate($this->userId,$fileId,$rate);
		}
		//get average rating file by computing
		$avgRateFile = $this->filesRatingMapper->computeAvgRateFile($fileId);
		$avgRateFile = number_format((float)$avgRateFile, 1, '.', '');

		//get all rating by file id
		$allRateFile = $this->filesRatingMapper->computeGroupRate($fileId);

		$data = [
			'rate_avg' => $avgRateFile ? $avgRateFile : '0',
			'rate_user' => $ratingUserFile ? $ratingUserFile->getRate(): '0',
			'rate_group' => $allRateFile ? $allRateFile : null
		];
        try {
			// $data = ['id' => '1', 'rate_avg' => $rate];
            return new DataResponse($data);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }
}
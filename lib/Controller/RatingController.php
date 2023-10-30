<?php
namespace OCA\FilesRating\Controller;

use Exception;
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
     * @param string $userId
	 * @param string $fileId
     * @return DataResponse
     */
    public function getInitialState(string $userId, string $fileId): DataResponse {
		//get average rating file
		//$avgRateFile = $this->filesRatingMapper->findAvgRateFile($fileId);

		//get average rating file by computing
		//$avgRateFile = $this->filesRatingMapper->computeAvgRateFile($fileId);

		//get all rating by file id
		//$allRateFile = $this->filesRatingMapper->findRateFile($fileId);

		//get rating current user and file id
		//$ratingUserFile =  $this->filesRatingMapper->findByUserAndFile($userId,$fileId);

		$data = ['id' => '1', 'rate_avg' => '3'];
        try {
            return new DataResponse($data);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @NoAdminRequired
     *
     * @param int $iddoc
     * @param int $rate
     * @return DataResponse
     */
    public function addFilesRating(int $id,?int $rate = null): DataResponse {
        try {
			$data = ['id' => '1', 'rate_avg' => $rate];
            return new DataResponse($data);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }
}
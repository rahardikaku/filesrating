<?php
namespace OCA\FilesRating\Controller;

use Exception;
// use OCA\NoteBook\Db\NoteMapper;
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
        // private NoteMapper $noteMapper,
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
     * @param int $iddoc
     * @return DataResponse
     */
    public function getInitialState(int $id): DataResponse {
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
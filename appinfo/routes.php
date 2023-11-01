<?php
declare(strict_types=1);
$requirements = [
    'apiVersion' => 'v1',
];
// SPDX-FileCopyrightText: Ano Rangga Rahardika <rahardikaku@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\FilesRating\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'ocs' => [
        ['name' => 'rating#getFilesRating', 'url' => '/api/{apiVersion}/rating/{id}', 'verb' => 'GET', 'requirements' => $requirements],
        ['name' => 'rating#getInitialState', 'url' => '/api/{apiVersion}/rating/initialstate/{fileId}', 'verb' => 'GET', 'requirements' => $requirements],
        ['name' => 'rating#addFilesRating', 'url' => '/api/{apiVersion}/rating/{fileId}', 'verb' => 'PUT', 'requirements' => $requirements],
    ],
];

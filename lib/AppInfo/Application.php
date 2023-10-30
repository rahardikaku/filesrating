<?php
// declare(strict_types=1);
// SPDX-FileCopyrightText: Ano Rangga Rahardika <rahardikaku@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\FilesRating\AppInfo;

use OCA\MyApp\Event\AddEvent;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\EventDispatcher\IEventDispatcher;
use OCA\Files\Event\LoadAdditionalScriptsEvent;

class Application extends App implements IBootstrap {
	public const APP_ID = 'filesrating';

	public function __construct() {
		parent::__construct(self::APP_ID);
		$dispatcher = $this->getContainer()->query(IEventDispatcher::class);
		$dispatcher->addListener(LoadAdditionalScriptsEvent::class, function() {
			// ...
			\OCP\Util::addScript(self::APP_ID, 'filesrating-main' );
			\OCP\Util::addscript(self::APP_ID, 'filesrating','files');
		});
	}

	public function boot(IBootContext $context): void {
	}

	public function register(IRegistrationContext $context): void {
	}

	public static function getL10N() {
		return \OC::$server->getL10N(self::APP_ID);
	}
}

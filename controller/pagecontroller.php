<?php
/**
 * ownCloud - galleryplus
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Robin Appelman <icewind@owncloud.com>
 * @author Olivier Paroz <owncloud@interfasys.ch>
 *
 * @copyright Robin Appelman 2012-2015
 * @copyright Olivier Paroz 2014-2015
 */

namespace OCA\GalleryPlus\Controller;

use OCP\IURLGenerator;
use OCP\IRequest;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;

use OCA\GalleryPlus\Environment\Environment;

/**
 * Generates templates for the landing page from within ownCloud, the public
 * gallery and error pages
 *
 * @package OCA\GalleryPlus\Controller
 */
class PageController extends Controller {

	/**
	 * @type Environment
	 */
	private $environment;
	/**
	 * @type IURLGenerator
	 */
	private $urlGenerator;

	/**
	 * Constructor
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param Environment $environment
	 * @param IURLGenerator $urlGenerator
	 */
	public function __construct(
		$appName,
		IRequest $request,
		Environment $environment,
		IURLGenerator $urlGenerator
	) {
		parent::__construct($appName, $request);

		$this->environment = $environment;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Shows the albums and pictures at the root folder or a message if
	 * there are no pictures.
	 *
	 * This is the entry page for logged-in users accessing the app from
	 * within ownCloud.
	 * A TemplateResponse response uses a template from the templates folder
	 * and parameters provided here to build the page users will see
	 *
	 * @return TemplateResponse
	 */
	public function index() {
		$appName = $this->appName;

		// Parameters sent to the template
		$params = ['appName' => $appName];

		// Will render the page using the template found in templates/index.php
		return new TemplateResponse($appName, 'index', $params);
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 *
	 * Shows the albums and pictures the token gives access to
	 *
	 * @param string $token
	 *
	 * @return TemplateResponse
	 */
	public function publicIndex($token) {
		$appName = $this->appName;
		$displayName = $this->environment->getDisplayName();
		$albumName = $this->environment->getSharedFolderName();

		// Parameters sent to the template
		$params = [
			'appName'     => $appName,
			'token'       => $token,
			'displayName' => $displayName,
			'albumName'   => $albumName
		];

		// Will render the page using the template found in templates/public.php
		return new TemplateResponse($appName, 'public', $params, 'public');
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 * @Guest
	 *
	 * Generates an error page based on the error code
	 *
	 * @param string $message
	 * @param int $code
	 *
	 * @return TemplateResponse
	 */
	public function errorPage($message, $code) {
		$appName = $this->appName;
		$params = [
			'appName' => $appName,
			'message' => $message,
			'code'    => $code,
		];

		$errorTemplate = new TemplateResponse($appName, 'index', $params, 'guest');
		$errorTemplate->setStatus($code);

		return $errorTemplate;
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 * @Guest
	 *
	 * Returns the slideshow template
	 *
	 * @return TemplateResponse
	 */
	public function slideshow() {
		return new TemplateResponse($this->appName, 'slideshow', [], 'blank');
	}
}

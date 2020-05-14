<?php
declare(strict_types=1);
/**
 * @author Lukas Reschke
 * @copyright 2014 Lukas Reschke lukas@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Files_PdfViewer\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\EmptyContentSecurityPolicy;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IURLGenerator;

class DisplayController extends Controller {

	/** @var IURLGenerator */
	private $urlGenerator;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 * @param IURLGenerator $urlGenerator
	 */
	public function __construct(string $AppName,
								IRequest $request,
								IURLGenerator $urlGenerator) {
		parent::__construct($AppName, $request);
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * @PublicPage
	 * @NoCSRFRequired
	 *
	 * @param bool $minmode
	 * @return TemplateResponse
	 */
	public function showPdfViewer(bool $minmode = false): TemplateResponse {
		$params = [
			'urlGenerator' => $this->urlGenerator,
			'minmode' => $minmode
		];
		$response = new TemplateResponse($this->appName, 'viewer', $params, 'blank');

		$policy = new EmptyContentSecurityPolicy();
		$policy->addAllowedChildSrcDomain('\'self\'');
		$policy->addAllowedChildSrcDomain('hypothes.is');
		$policy->addAllowedFontDomain('data:');
		$policy->addAllowedConnectDomain('*.hypothes.is');
		$policy->addAllowedConnectDomain('\'self\'');
		$policy->addAllowedScriptDomain('*.hypothes.is');
		$policy->addAllowedScriptDomain('hypothes.is');
		$policy->addAllowedScriptDomain('\'self\'');
		$policy->addAllowedScriptDomain('\'unsafe-eval\'');
		$policy->addAllowedStyleDomain('\'unsafe-inline\'');
		$policy->addAllowedStyleDomain('\'self\'');
		$policy->addAllowedStyleDomain('*.hypothes.is');
		$policy->addAllowedImageDomain('*');
		$policy->allowEvalScript(false);
		$response->setContentSecurityPolicy($policy);

		return $response;
	}

}

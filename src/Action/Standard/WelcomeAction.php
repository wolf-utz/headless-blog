<?php

declare(strict_types=1);

namespace App\Action\Standard;

use OmegaCode\JwtSecuredApiCore\Action\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WelcomeAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        ob_start();
        include __DIR__ . '/../../../res/welcome.php';
        $content = ob_get_contents();
        ob_end_clean();
        $response->getBody()->write((string) $content);

        return $response;
    }
}

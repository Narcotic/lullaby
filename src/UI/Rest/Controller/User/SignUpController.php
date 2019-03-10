<?php /** @noinspection PhpParamsInspection */

namespace App\UI\Rest\Controller\User;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\UI\Rest\Controller\Controller;
use App\UI\Rest\Response\ViolationResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends Controller
{
    /**
     * @Route("/users", name="user_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $signUpCommand = $this->deserialize($request->getContent(), SignUpCommand::class);

        try {
            $this->commandBus->dispatch($signUpCommand);
        } catch (ValidationFailedException $e) {
            return new ViolationResponse($e->getViolations(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($signUpCommand);
    }
}
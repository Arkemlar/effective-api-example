<?php

declare(strict_types=1);

namespace App\Api\Examples\Rest\Actions;

use App\Api\Http\Contract\HttpApiActionInterface;
use App\Api\Http\Contract\ValidRequestInterface;
use App\Api\Http\Dataset\ValidationSequence\ValidationSequence;
use App\Api\Http\Dataset\ValidationSequence\ValidationSequenceBuilder;
use App\Api\Http\HttpActionExecutor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

final class SayHelloAction implements HttpApiActionInterface
{
    /**
     * @Route("/request/assistant", name="request_assistant_all", methods={"GET"})
     */
    public function __invoke(HttpActionExecutor $executor, Request $request)
    {
        $executor->processRequest($request, $this);
    }

    public function getDataset(Request $request): ValidationSequence
    {
        $sequence = ValidationSequenceBuilder::new()
            ->forDataset($request->getContent(), 'content')
            ->addConstraint(new Assert\Collection([
                'names' => new Assert\All([
                    new Assert\Type('string'),
                    new Assert\Length(['min' => 2, 'max' => 15])
                ]),
                'is_kindly' => new Assert\Type('bool'),
            ]));

        return $sequence->getSequence();
    }

    public function execute(ValidRequestInterface $request1)
    {
        // TODO: Implement execute() method.
    }
}
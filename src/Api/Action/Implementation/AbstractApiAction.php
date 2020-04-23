<?php

declare(strict_types=1);

namespace App\Api\Action\Implementation;

use App\Api\Action\Contract\ApiActionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraint;

abstract class AbstractApiAction implements ApiActionInterface
{
    protected Constraint $constraints;
    protected NormalizerInterface $normalizer;

    /**
     * @required
     */
    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    public function getConstraints(): Constraint
    {
        return $this->constraints;
    }
}

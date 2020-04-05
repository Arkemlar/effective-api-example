<?php

declare(strict_types=1);

namespace App\Api;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraint;

abstract class AbstractApiAction implements ApiActionInterface
{
    /** @var Constraint|null */
    protected $constraints;
    /** @var NormalizerInterface */
    protected $normalizer;

    /**
     * @required
     */
    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    public function getConstraints(): ?Constraint
    {
        return $this->constraints;
    }
}

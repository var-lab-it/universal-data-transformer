<?php

declare(strict_types=1);

namespace App\Config\Transformation;

use Symfony\Component\Validator\Constraints as Assert;

class WordPressTransformationConfiguration extends AbstractTransformationConfiguration
{
    public function getMappingValidationConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'post' => new Assert\Collection([
                'title'   => $this->getPropertyMappingValidationConstraint(),
                'pubDate' => $this->getPropertyMappingValidationConstraint(),
                'content' => $this->getPropertyMappingValidationConstraint(),
                'excerpt' => $this->getPropertyMappingValidationConstraint(),
            ]),
        ]);
    }

    private function getPropertyMappingValidationConstraint(): Assert\Collection
    {
        return new Assert\Collection([
            'table'  => new Assert\Type('string'),
            'column' => new Assert\Type('string'),
        ]);
    }
}

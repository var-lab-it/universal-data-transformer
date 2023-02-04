<?php

declare(strict_types=1);

namespace App\Config\Transformation;

use Symfony\Component\Validator\Constraints as Assert;

class WordPressTransformationConfiguration extends AbstractTransformationConfiguration
{
    public function getMapping(): array
    {
        // TODO: Implement getMapping() method.
    }

    public static function getMappingValidationConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'post' => new Assert\Collection([
                'title'   => new Assert\NotBlank([], 'Mapping for title property cannot be not empty.'),
                'pubDate' => new Assert\NotBlank([], 'Mapping for title property cannot be not empty.'),
                'content' => new Assert\NotBlank([], 'Mapping for title property cannot be not empty.'),
                'excerpt' => new Assert\NotBlank([], 'Mapping for title property cannot be not empty.'),
            ]),
        ]);
    }
}

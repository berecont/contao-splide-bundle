<?php

declare(strict_types=1);

namespace Berecont\ContaoSplideBundle\EventListener\DataContainer;

use Symfony\Contracts\Translation\TranslatorInterface;
use Contao\DataContainer;

final class SplideTransitionOptionsListener
{
    /**
     * @param array<string, array{
     *     label?: string,
     *     type?: string,
     *     script?: string|null,
     *     component?: string|null
     * }> $transitions
     */
    public function __construct(
        private readonly array $transitions,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function getTransitionOptions(): array
    {
        $options = [];

        foreach ($this->transitions as $name => $configuration) {
            $label = (string) ($configuration['label'] ?? $name);

            $options[$name] = $this->translator->trans(
                $label,
                [],
                'contao_default',
            );
        }

        return $options;
    }

    public function validateJson(
        mixed $value,
        DataContainer|null $dataContainer = null,
    ): string {
        $value = trim((string) $value);

        if ('' === $value) {
            return '';
        }

        try {
            $decoded = json_decode(
                $value,
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (\JsonException $exception) {
            throw new \InvalidArgumentException(
                $this->translator->trans(
                    'contao_splide.error.invalid_json',
                    [
                        '%message%' => $exception->getMessage(),
                    ],
                    'contao_default',
                ),
            );
        }

        if (!\is_array($decoded) || array_is_list($decoded)) {
            throw new \InvalidArgumentException(
                $this->translator->trans(
                    'contao_splide.error.json_object_required',
                    [],
                    'contao_default',
                ),
            );
        }

        return json_encode(
            $decoded,
            JSON_THROW_ON_ERROR
            | JSON_PRETTY_PRINT
            | JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE,
        );
    }
}
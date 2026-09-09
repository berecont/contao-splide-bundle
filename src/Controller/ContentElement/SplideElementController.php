<?php

declare(strict_types=1);

namespace Berecont\ContaoSplideBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(
    type: 'splide',
    category: 'media',
    template: 'content_element/splide',
    nestedFragments: true,
)]
final class SplideElementController extends AbstractContentElementController
{
    /**
     * @param array<string, array<string, mixed>> $transitions
     */
    public function __construct(
        private readonly array $transitions,
    ) {
    }

    protected function getResponse(
        FragmentTemplate $template,
        ContentModel $model,
        Request $request,
    ): Response {
        $autoScrollEnabled = (bool) $model->splideAutoScroll;
        $transitionName = $this->resolveTransitionName(
            (string) $model->splideTransition,
            $autoScrollEnabled,
        );
        $transition = $this->transitions[$transitionName] ?? [];

        $interval = max(0, (int) $model->splideInterval);
        $isContinuous = (bool) $model->splideLoop;
        $isFade = 'fade' === $transitionName;

        $options = $this->buildBaseOptions(
            $model,
            $transitionName,
            $interval,
            $isContinuous,
        );

        $this->applyLayoutOptions(
            $options,
            $model,
            $isFade,
        );

        $this->applyResponsiveOptions(
            $options,
            $model,
        );

        $this->applyExpertOptions(
            $options,
            $model,
        );

        /*
         * AutoScroll wird ausschließlich über die Backend-Checkbox
         * aktiviert. Ein autoScroll-Eintrag in den Expertenoptionen
         * wird deshalb bei deaktivierter Checkbox entfernt.
         */
        unset($options['autoScroll']);

        if ($autoScrollEnabled) {
            $this->applyAutoScrollOptions(
                $options,
                $model,
            );
        }

        /*
         * Fade-Einschränkungen werden nach den Expertenoptionen nochmals
         * durchgesetzt. AutoScroll erzwingt bereits den Übergang "slide"
         * und gelangt deshalb nicht in diesen Zweig.
         */
        if ($isFade) {
            $this->applyFadeRestrictions(
                $options,
                $isContinuous,
            );
        }

        $transitionScript = trim(
            (string) ($transition['script'] ?? ''),
        );
        $transitionComponent = trim(
            (string) ($transition['component'] ?? ''),
        );

        $template->set('splide_id', sprintf(
            'splide-%d',
            (int) $model->id,
        ));
        $template->set('splide_options', $options);
        $template->set(
            'splide_classes',
            $this->normalizeCssClasses(
                (string) $model->splideClasses,
            ),
        );
        $template->set(
            'splide_aria_label',
            $this->getAriaLabel($model),
        );
        $template->set('splide_transition', $transitionName);
        $template->set(
            'splide_transition_script',
            '' !== $transitionScript
                ? $transitionScript
                : null,
        );
        $template->set(
            'splide_transition_component',
            '' !== $transitionComponent
                ? $transitionComponent
                : null,
        );
        $template->set(
            'splide_uses_autoscroll',
            $autoScrollEnabled,
        );
        $template->set(
            'splide_show_autoplay_toggle',
            !$autoScrollEnabled
            && $interval > 0
            && (bool) $model->splideShowAutoplayToggle,
        );

        return $template->getResponse();
    }

    private function resolveTransitionName(
        string $transitionName,
        bool $autoScrollEnabled,
    ): string {
        /*
         * AutoScroll arbeitet als kontinuierliche Slide-Bewegung.
         * Fade- und benutzerdefinierte Übergänge werden dabei bewusst
         * nicht verwendet.
         */
        if ($autoScrollEnabled) {
            return 'slide';
        }

        $transitionName = trim($transitionName);

        if (
            '' === $transitionName
            || !isset($this->transitions[$transitionName])
        ) {
            return 'slide';
        }

        return $transitionName;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildBaseOptions(
        ContentModel $model,
        string $transitionName,
        int $interval,
        bool $isContinuous,
    ): array {
        $isFade = 'fade' === $transitionName;
        $speed = max(0, (int) $model->splideSpeed);

        return [
            'type' => $isFade
                ? 'fade'
                : ($isContinuous ? 'loop' : 'slide'),
            'start' => max(0, (int) $model->splideStart),
            'speed' => $speed > 0 ? $speed : 400,
            'rewind' => $isFade && $isContinuous,
            'autoplay' => $interval > 0,
            'interval' => $interval > 0 ? $interval : 5000,
            'pauseOnHover' => (bool) $model->splidePauseOnHover,
            'pauseOnFocus' => (bool) $model->splidePauseOnFocus,
            'arrows' => !(bool) $model->splideHideArrows,
            'pagination' => !(bool) $model->splideHidePagination,
        ];
    }

    /**
     * @param array<string, mixed> $options
     */
    private function applyLayoutOptions(
        array &$options,
        ContentModel $model,
        bool $isFade,
    ): void {
        $options['perPage'] = $isFade
            ? 1
            : max(1, (int) $model->splidePerPage);
        $options['perMove'] = $isFade
            ? 1
            : max(1, (int) $model->splidePerMove);

        $gap = trim((string) $model->splideGap);

        if ('' !== $gap) {
            $options['gap'] = $gap;
        }

        if (
            (bool) $model->splideCentered
            && !$isFade
        ) {
            $options['focus'] = 'center';
            $options['trimSpace'] = false;
        }
    }

    /**
     * @param array<string, mixed> $options
     */
    private function applyResponsiveOptions(
        array &$options,
        ContentModel $model,
    ): void {
        $breakpoints = $this->decodeJsonObject(
            (string) $model->splideBreakpoints,
        );

        if ([] !== $breakpoints) {
            $options['breakpoints'] = $breakpoints;
        }
    }

    /**
     * @param array<string, mixed> $options
     */
    private function applyExpertOptions(
        array &$options,
        ContentModel $model,
    ): void {
        $customOptions = $this->decodeJsonObject(
            (string) $model->splideOptions,
        );

        if ([] === $customOptions) {
            return;
        }

        $options = array_replace_recursive(
            $options,
            $customOptions,
        );
    }

    /**
     * @param array<string, mixed> $options
     */
    private function applyAutoScrollOptions(
        array &$options,
        ContentModel $model,
    ): void {
        $speed = $this->normalizeAutoScrollSpeed(
            (string) $model->splideAutoScrollSpeed,
        );

        /*
         * AutoScroll benötigt einen Loop-Slider. Normales Autoplay und
         * dessen Intervall werden deaktiviert, damit beide Mechanismen
         * nicht gegeneinander arbeiten.
         */
        $options['type'] = 'loop';
        $options['rewind'] = false;
        $options['drag'] = 'free';
        $options['autoplay'] = false;
        $options['autoScroll'] = [
            'speed' => $speed,
            'pauseOnHover' => (bool) $model->splidePauseOnHover,
            'pauseOnFocus' => (bool) $model->splidePauseOnFocus,
        ];

        unset($options['interval']);
    }

    private function normalizeAutoScrollSpeed(string $value): float
    {
        $value = trim(
            str_replace(',', '.', $value),
        );

        if (!is_numeric($value)) {
            return 0.5;
        }

        $speed = (float) $value;

        /*
         * Mit 0 würde AutoScroll zwar gemountet, aber sichtbar stillstehen.
         * Daher wird bei 0 auf den Standardwert zurückgefallen.
         */
        return 0.0 !== $speed
            ? $speed
            : 0.5;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function applyFadeRestrictions(
        array &$options,
        bool $isContinuous,
    ): void {
        $options['type'] = 'fade';
        $options['perPage'] = 1;
        $options['perMove'] = 1;
        $options['rewind'] = $isContinuous;

        unset(
            $options['focus'],
            $options['trimSpace'],
            $options['drag'],
            $options['autoScroll'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJsonObject(string $value): array
    {
        $value = trim($value);

        if ('' === $value) {
            return [];
        }

        try {
            $decoded = json_decode(
                $value,
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (\JsonException) {
            return [];
        }

        return \is_array($decoded)
            ? $decoded
            : [];
    }

    /**
     * @return list<string>
     */
    private function normalizeCssClasses(string $value): array
    {
        $classes = preg_split(
            '/\s+/',
            trim($value),
            -1,
            PREG_SPLIT_NO_EMPTY,
        );

        if (false === $classes) {
            return [];
        }

        $classes = array_filter(
            $classes,
            static fn (string $class): bool => 1 === preg_match(
                '/^-?[_a-zA-Z]+[_a-zA-Z0-9-]*$/',
                $class,
            ),
        );

        return array_values(
            array_unique($classes),
        );
    }

    private function getAriaLabel(ContentModel $model): string
    {
        $ariaLabel = trim(
            (string) $model->splideAriaLabel,
        );

        if ('' !== $ariaLabel) {
            return $ariaLabel;
        }

        $headline = StringUtil::deserialize(
            $model->headline,
            true,
        );

        if (
            \is_array($headline)
            && isset($headline['value'])
            && \is_string($headline['value'])
        ) {
            $headlineValue = trim(
                strip_tags($headline['value']),
            );

            if ('' !== $headlineValue) {
                return $headlineValue;
            }
        }

        return 'Slider';
    }
}

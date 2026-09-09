<?php

declare(strict_types=1);

use Berecont\ContaoSplideBundle\EventListener\DataContainer\SplideTransitionOptionsListener;

$GLOBALS['TL_DCA']['tl_content']['palettes']['splide'] = '
    {type_legend},type,headline,title;
    {splide_transition_legend},
        splideTransition,
        splideInterval,
        splideSpeed,
        splideStart,
        splideLoop,
        splideCentered;
    {splide_layout_legend},
        splidePerPage,
        splidePerMove,
        splideGap;
    {splide_autoscroll_legend},
        splideAutoScroll;
    {splide_behavior_legend},
        splidePauseOnHover,
        splidePauseOnFocus;
    {splide_navigation_legend},
        splideHideArrows,
        splideHidePagination,
        splideShowAutoplayToggle;
    {splide_accessibility_legend},
        splideAriaLabel;
    {splide_expert_legend:hide},
        splideClasses,
        splideBreakpoints,
        splideOptions;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},cssID;
    {invisible_legend:hide},invisible,start,stop
';

$selectors = &$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'];

if (!\in_array('splideAutoScroll', $selectors, true)) {
    $selectors[] = 'splideAutoScroll';
}

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['splideAutoScroll'] =
    'splideAutoScrollSpeed';

$GLOBALS['TL_DCA']['tl_content']['fields']['splideTransition'] = [
    'inputType' => 'select',
    'options_callback' => [
        SplideTransitionOptionsListener::class,
        'getTransitionOptions',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval' => [
        'mandatory' => true,
        'tl_class' => 'w50',
    ],
    'sql' => [
        'type' => 'string',
        'length' => 32,
        'default' => 'slide',
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideInterval'] = [
    'inputType' => 'text',
    'eval' => [
        'rgxp' => 'natural',
        'tl_class' => 'w50',
    ],
    'sql' => [
        'type' => 'integer',
        'unsigned' => true,
        'default' => 0,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideSpeed'] = [
    'inputType' => 'text',
    'eval' => [
        'rgxp' => 'natural',
        'tl_class' => 'w50',
    ],
    'sql' => [
        'type' => 'integer',
        'unsigned' => true,
        'default' => 400,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideStart'] = [
    'inputType' => 'text',
    'eval' => [
        'rgxp' => 'natural',
        'tl_class' => 'w50',
    ],
    'sql' => [
        'type' => 'integer',
        'unsigned' => true,
        'default' => 0,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideLoop'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideCentered'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splidePerPage'] = [
    'inputType' => 'text',
    'eval' => [
        'mandatory' => true,
        'rgxp' => 'natural',
        'tl_class' => 'w33',
    ],
    'sql' => [
        'type' => 'integer',
        'unsigned' => true,
        'default' => 1,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splidePerMove'] = [
    'inputType' => 'text',
    'eval' => [
        'mandatory' => true,
        'rgxp' => 'natural',
        'tl_class' => 'w33',
    ],
    'sql' => [
        'type' => 'integer',
        'unsigned' => true,
        'default' => 1,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideGap'] = [
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 32,
        'tl_class' => 'w33',
    ],
    'sql' => [
        'type' => 'string',
        'length' => 32,
        'default' => '',
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideAutoScroll'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'submitOnChange' => true,
        'tl_class' => 'w50 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideAutoScrollSpeed'] = [
    'inputType' => 'text',
    'default' => '0.5',
    'eval' => [
        'maxlength' => 16,
        'tl_class' => 'w50',
    ],
    'sql' => [
        'type' => 'string',
        'length' => 16,
        'default' => '0.5',
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splidePauseOnHover'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splidePauseOnFocus'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideHideArrows'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w33 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideHidePagination'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w33 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideShowAutoplayToggle'] = [
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w33 m12',
    ],
    'sql' => [
        'type' => 'boolean',
        'default' => false,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideAriaLabel'] = [
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 255,
        'tl_class' => 'long',
    ],
    'sql' => [
        'type' => 'string',
        'length' => 255,
        'default' => '',
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideClasses'] = [
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 255,
        'tl_class' => 'long',
    ],
    'sql' => [
        'type' => 'string',
        'length' => 255,
        'default' => '',
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideBreakpoints'] = [
    'inputType' => 'textarea',
    'save_callback' => [
        [
            SplideTransitionOptionsListener::class,
            'validateJson',
        ],
    ],
    'eval' => [
        'allowHtml' => false,
        'decodeEntities' => true,
        'class' => 'monospace',
        'tl_class' => 'clr long',
    ],
    'sql' => [
        'type' => 'text',
        'notnull' => false,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['splideOptions'] = [
    'inputType' => 'textarea',
    'save_callback' => [
        [
            SplideTransitionOptionsListener::class,
            'validateJson',
        ],
    ],
    'eval' => [
        'allowHtml' => false,
        'decodeEntities' => true,
        'class' => 'monospace',
        'tl_class' => 'clr long',
    ],
    'sql' => [
        'type' => 'text',
        'notnull' => false,
    ],
];

(() => {
    'use strict';

    const selector = '[data-splide-slider]';

    /**
     * Löst einen globalen Pfad wie
     * "BerecontSplideTransitions.zoom" auf.
     *
     * @param {string} path
     * @returns {*}
     */
    const resolveGlobal = (path) => {
        if (!path) {
            return null;
        }

        return path
            .split('.')
            .reduce(
                (value, segment) => value?.[segment],
                window
            );
    };

    /**
     * Liefert nur die Extensions zurück, die von dieser
     * konkreten Slider-Instanz benötigt werden.
     *
     * @param {Record<string, *>} options
     * @param {HTMLElement} element
     * @returns {Record<string, Function>}
     */
    const getExtensions = (options, element) => {
        const extensions = {};

        if (
            !options.autoScroll
            || typeof options.autoScroll !== 'object'
        ) {
            return extensions;
        }

        const autoScroll =
            window.splide?.Extensions?.AutoScroll;

        if (typeof autoScroll !== 'function') {
            console.error(
                'AutoScroll ist für diesen Slider aktiviert, '
                + 'aber die Splide-AutoScroll-Extension wurde nicht geladen.',
                element
            );

            return extensions;
        }

        extensions.AutoScroll = autoScroll;

        return extensions;
    };

    /**
     * @param {HTMLElement} element
     */
    const initialize = (element) => {
        if (
            !(element instanceof HTMLElement)
            || element.dataset.splideInitialized === 'true'
        ) {
            return;
        }

        let options = {};

        try {
            options = JSON.parse(
                element.dataset.splideOptions || '{}'
            );
        } catch (error) {
            console.error(
                'Ungültige Splide-Optionen:',
                error,
                element
            );

            return;
        }

        if (typeof window.Splide !== 'function') {
            console.error(
                'SplideJS wurde nicht geladen.',
                element
            );

            return;
        }

        const transitionComponent = resolveGlobal(
            element.dataset.splideTransitionComponent
        );

        const extensions = getExtensions(
            options,
            element
        );

        const splide = new window.Splide(
            element,
            options
        );

        if (typeof transitionComponent === 'function') {
            splide.mount(
                extensions,
                transitionComponent
            );
        } else {
            splide.mount(extensions);
        }

        element.dataset.splideInitialized = 'true';
        element.splide = splide;
    };

    const initializeAll = (root = document) => {
        if (root.matches?.(selector)) {
            initialize(root);
        }

        root
            .querySelectorAll?.(selector)
            .forEach(initialize);
    };

    const boot = () => {
        initializeAll();

        const observer = new MutationObserver(
            (mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach(
                        (node) => {
                            if (
                                node instanceof HTMLElement
                            ) {
                                initializeAll(node);
                            }
                        }
                    );
                });
            }
        );

        observer.observe(document.documentElement, {
            childList: true,
            subtree: true,
        });
    };

    /*
     * DOMContentLoaded wird erst ausgelöst, nachdem alle defer-Skripte
     * ausgeführt wurden. Damit ist die AutoScroll-Extension unabhängig
     * von der Reihenfolge der Contao-Asset-Blöcke verfügbar.
     */
    if ('complete' === document.readyState) {
        boot();
    } else {
        document.addEventListener(
            'DOMContentLoaded',
            boot,
            {
                once: true,
            }
        );
    }
})();
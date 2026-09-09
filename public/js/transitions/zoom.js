(() => {
    'use strict';

    window.BerecontSplideTransitions =
        window.BerecontSplideTransitions || {};

    window.BerecontSplideTransitions.zoom = (
        Splide,
        Components
    ) => {
        const { Move, Elements } = Components;
        const { list } = Elements;

        let endCallback = null;
        let fallbackTimer = null;

        /**
         * Beendet den aktuellen Übergang.
         */
        const finish = () => {
            if (fallbackTimer !== null) {
                window.clearTimeout(fallbackTimer);
                fallbackTimer = null;
            }

            list.style.transition = '';

            if (typeof endCallback === 'function') {
                const callback = endCallback;

                endCallback = null;
                callback();
            }
        };

        /**
         * Reagiert auf das Ende der Listenbewegung.
         *
         * @param {TransitionEvent} event
         */
        const onTransitionEnd = (event) => {
            if (
                event.target !== list
                || event.propertyName !== 'transform'
            ) {
                return;
            }

            finish();
        };

        /**
         * Wird beim Mounten der Transition aufgerufen.
         */
        const mount = () => {
            list.addEventListener(
                'transitionend',
                onTransitionEnd
            );
        };

        /**
         * Führt die Bewegung zum Ziel-Slide aus.
         *
         * @param {number} index
         * @param {Function} done
         */
        const start = (index, done) => {
            const speed = Math.max(
                0,
                Number(Splide.options.speed) || 0
            );

            const destination = Move.toPosition(
                index,
                true
            );

            endCallback = done;

            list.style.transition = [
                'transform',
                `${speed}ms`,
                'cubic-bezier(.44,.65,.07,1.01)',
            ].join(' ');

            Move.translate(destination);

            /*
             * Sicherheitsfallback:
             * transitionend wird beispielsweise bei 0 ms oder bei
             * identischer Position unter Umständen nicht ausgelöst.
             */
            fallbackTimer = window.setTimeout(
                finish,
                speed + 100
            );
        };

        /**
         * Bricht einen laufenden Übergang ab.
         */
        const cancel = () => {
            if (fallbackTimer !== null) {
                window.clearTimeout(fallbackTimer);
                fallbackTimer = null;
            }

            list.style.transition = '';
            endCallback = null;
        };

        /**
         * Entfernt den nativen Event-Listener.
         */
        const destroy = () => {
            cancel();

            list.removeEventListener(
                'transitionend',
                onTransitionEnd
            );
        };

        return {
            mount,
            start,
            cancel,
            destroy,
        };
    };
})();
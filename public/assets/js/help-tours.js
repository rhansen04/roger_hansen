/**
 * Help Tours Loader — Driver.js
 * Detecta a página atual e carrega o tour correspondente.
 */
(function() {
    'use strict';

    // Mapa de rotas para tours
    var routeMap = {
        '/admin/dashboard':           { tour: 'dashboard',               script: 'dashboard.js' },
        '/admin/courses':             { tour: 'courses',                 script: 'courses.js' },
        '/admin/planning-templates':  { tour: 'planning-templates',      script: 'planning-templates.js' },
        '/admin/planning':            { tour: 'planning',                script: 'planning.js' }
    };

    // Detecta sub-rotas (form/edit/create)
    var formRoutes = {
        '/admin/planning-templates/create': 'planning-templates-form',
        '/admin/planning-templates/edit':   'planning-templates-form',
        '/admin/planning/create':           'planning-form',
        '/admin/planning/edit':             'planning-form'
    };

    function getCurrentPath() {
        return window.location.pathname.replace(/\/+$/, '') || '/';
    }

    function findTourConfig(path) {
        // Check form routes first (more specific)
        for (var route in formRoutes) {
            if (path.indexOf(route) === 0) {
                // Find the base route for script loading
                var baseRoute = route.replace(/\/(create|edit)$/, '');
                return {
                    tour: formRoutes[route],
                    script: routeMap[baseRoute] ? routeMap[baseRoute].script : null
                };
            }
        }
        // Check exact/prefix routes
        for (var route in routeMap) {
            if (path === route || path.indexOf(route + '/') === 0) {
                return routeMap[route];
            }
        }
        return null;
    }

    function loadScript(src, callback) {
        var s = document.createElement('script');
        s.src = src;
        s.onload = callback;
        s.onerror = function() { console.warn('Help tour script not found:', src); };
        document.head.appendChild(s);
    }

    function startTour(tourName) {
        if (!window.helpTours || !window.helpTours[tourName]) {
            console.warn('Tour not found:', tourName);
            return;
        }

        var config = window.helpTours[tourName];
        // Filter steps to only include elements that exist on the page
        var validSteps = config.steps.filter(function(step) {
            return !step.element || document.querySelector(step.element);
        });

        if (validSteps.length === 0) {
            alert('Nenhum elemento do tour foi encontrado nesta página.');
            return;
        }

        var driver = window.driver.js.driver({
            showProgress: true,
            animate: true,
            overlayColor: 'rgba(0, 0, 0, 0.6)',
            stagePadding: 8,
            stageRadius: 8,
            popoverClass: 'hansen-tour-popover',
            nextBtnText: 'Próximo →',
            prevBtnText: '← Anterior',
            doneBtnText: 'Concluir ✓',
            progressText: '{{current}} de {{total}}',
            steps: validSteps
        });

        driver.drive();
    }

    // Public API
    window.HelpTours = {
        start: function() {
            var path = getCurrentPath();
            var config = findTourConfig(path);

            if (!config) {
                alert('Não há tour disponível para esta página ainda.');
                return;
            }

            if (window.helpTours && window.helpTours[config.tour]) {
                startTour(config.tour);
            } else if (config.script) {
                loadScript('/assets/js/help-tours/' + config.script, function() {
                    startTour(config.tour);
                });
            }
        },

        hasTour: function() {
            return findTourConfig(getCurrentPath()) !== null;
        }
    };
})();

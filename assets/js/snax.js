(() => {
    const slmSnaxParams = {
        loaderContainerSelector    : '#slm-loader-container-snax',
        loaderHolderSelector       : '.slm-loader-holder',
        loadingClass               : 'slm-loading',
        loaderCoverClass           : 'slm-cover',
        ctaContainerSelector       : '#slm-cta-container-snax',
        ctaHolderSelector          : '.slm-cta-holder',
        ctaButtonSelector          : '[data-slm-cta]',
        surveyBlockClass           : 'slm-snax-survey-holder',
        additionalElementsSelector : '.g1-newsletter',
        resultsSelector            : '.snax-quiz-results',
        surveyIframeSelector       : slm.getSelector('LnN1cnZhdGEtZWxlbWVudC1jb250YWluZXIgaWZyYW1l'),
        heightFixed                : false
    };

    const slmSnaxInit = ($, $snaxContainer, $questions) => {
        const slmSnax = {
            _interviewStarted  : false,
            _surveyBlock       : false,
            _initSurveyBlock   : () => {
                if (slmSnax._surveyBlock === false) {
                    slmSnax._surveyBlock = $('<div />', {'class' : slmSnaxParams.surveyBlockClass}).insertAfter($snaxContainer);
                }
            },
            getSnaxContainer   : () => $snaxContainer,
            getSurveyBlock     : () => {
                slmSnax._initSurveyBlock();
                return slmSnax._surveyBlock;
            },
            showCTA            : callback => {
                slmSnax.removeCTA();

                const cta = $(slmSnaxParams.ctaContainerSelector).find(slmSnaxParams.ctaHolderSelector).clone();
                slmSnax.getSurveyBlock().prepend(cta)
                slmSnax.removeSurveyLoader();

                cta.on('click', slmSnaxParams.ctaButtonSelector, callback);
            },
            removeCTA          : (fade = false) => {
                const cta = slmSnax.getSurveyBlock().find(slmSnaxParams.ctaHolderSelector);

                if (fade) {
                    cta.fadeOut('fast', cta.remove);
                } else {
                    cta.remove();
                }
            },
            hideQuestions      : () => {
                $questions.hide();
            },
            hideSnaxElements   : () => {
                slmSnax.getSnaxContainer().fadeOut('fast', () => {
                    $([document.documentElement, document.body]).animate({
                        scrollTop : Math.max(slmSnax.getSurveyBlock().offset().top - 150, 0)
                    }, 1000);
                });
                $(slmSnaxParams.additionalElementsSelector).fadeOut('fast');
            },
            addSurveyLoader    : (coverClass = '') => {
                slmSnax.removeSurveyLoader();
                slmSnax.getSurveyBlock()
                    .prepend($(slmSnaxParams.loaderContainerSelector).find(slmSnaxParams.loaderHolderSelector).clone())
                    .addClass(slmSnaxParams.loadingClass)
                    .addClass(coverClass);
            },
            removeSurveyLoader : (fade = false) => {
                const loader = slmSnax.getSurveyBlock().find(slmSnaxParams.loaderHolderSelector);

                if (fade) {
                    loader.fadeOut('fast', () => {
                        slmSnax.getSurveyBlock()
                            .removeClass(slmSnaxParams.loadingClass)
                            .removeClass(slmSnaxParams.loaderCoverClass);
                        loader.remove();
                    })
                } else {
                    slmSnax.getSurveyBlock()
                        .removeClass(slmSnaxParams.loadingClass)
                        .removeClass(slmSnaxParams.loaderCoverClass);
                    loader.remove();
                }

                slmSnax.getSurveyBlock()
                    .removeClass(slmSnaxParams.loadingClass)
                    .removeClass(slmSnaxParams.loaderCoverClass)
                    .find(slmSnaxParams.loaderHolderSelector)
                    .remove();
            },
            showResults        : (surveyClass = '') => {
                slmSnax.removeCTA();
                slmSnax.getSurveyBlock().addClass(surveyClass);

                slmSnax.addSurveyLoader();
                slmSnax.getSurveyBlock().fadeOut('fast', () => {
                    slmSnax.getSnaxContainer()
                        .find(slmSnaxParams.resultsSelector)
                        .addClass('slm-snax-show');

                    slmSnax.getSnaxContainer()
                        .hide()
                        .fadeIn('fast');

                    $(slmSnaxParams.additionalElementsSelector).fadeIn('fast');

                    slmSnax.getSurveyBlock().remove();
                })
            },
            fixSurveyHeight    : () => {
                const $iframe = slmSnax.getSurveyBlock().find(slmSnaxParams.surveyIframeSelector);

                if (!slmSnaxParams.heightFixed) {
                    slmSnax.getSurveyBlock().height(700);
                    if (parseInt($iframe.height(), 10) < 800) {
                        $iframe.height('700');
                        slmSnaxParams.heightFixed = true;
                    }
                }
            },
            showInterview      : () => {
                if (slmSnax._interviewStarted === false) {
                    slmSnax.removeCTA();
                    slmSnax.removeSurveyLoader(true);
                    slmSnax.fixSurveyHeight();
                    setTimeout(slmSnax.fixSurveyHeight, 600);
                    setTimeout(slmSnax.fixSurveyHeight, 1600);
                    setTimeout(slmSnax.fixSurveyHeight, 3200);

                    slmSnax._interviewStarted = true;
                }
            },
        };

        slmSnax.hideSnaxElements();
        slmSnax.hideQuestions();
        slmSnax.addSurveyLoader();

        slm.init();
        slm.readyCallback(() => {
            slm.trigger('slm.snax.loading');
            slm.log('SurveyLock.me Snax addon:: Loading interview');

            const s = slm.createSurveywall({
                parent    : slmSnax.getSurveyBlock(),
                brand     : slmSnaxConfig.brand,
                explainer : slmSnaxConfig.explainer,
            });

            s.on('load', function (data) {
                slm.trigger('slm.snax.loaded', data.status);
                slm.log('SurveyLock.me Snax addon:: Interview loaded. Status: ' + data.status);

                slmSnax.showCTA(() => {
                    if (data.status === 'monetizable') {
                        slmSnax.removeCTA();
                        slmSnax.addSurveyLoader(slmSnaxParams.loaderCoverClass);
                        s.startInterview();
                    } else if (data.status === 'earnedCredit') {
                        slmSnax.showResults('slm-snax-taken');
                    } else {
                        slmSnax.showResults('slm-snax-unavailable');
                    }

                    return false;
                });
            });

            s.on('interviewStart', function () {
                slm.trigger('slm.snax.started');
                slm.log('SurveyLock.me Snax addon:: Interview started');

                slmSnax.getSurveyBlock()
                    .find(slmSnaxParams.surveyIframeSelector)
                    .on('load', slmSnax.showInterview);

                setTimeout(slmSnax.showInterview, 1000);
            });
            s.on('interviewComplete', function () {
                slm.trigger('slm.snax.completed');
                slm.log('SurveyLock.me Snax addon:: Interview completed');

                slmSnax.showResults('slm-snax-completed');
            });
            s.on('interviewAbandon', function () {
                slm.trigger('slm.snax.abandoned');
                slm.log('SurveyLock.me Snax addon:: Interview abandoned');

                slmSnax.showResults('slm-snax-abandoned');
            });
            s.on('interviewSkip', function () {
                slm.trigger('slm.snax.skipped');
                slm.log('SurveyLock.me Snax addon:: Interview skipped');

                slmSnax.showResults('slm-snax-skipped');
            });

        });
        slm.failCallback(() => {
            slm.trigger('slm.snax.failed');
            slm.log('SurveyLock.me Snax addon:: Survey failed');

            slmSnax.showResults('slm-snax-failed');
        });
    }


    jQuery(function ($) {
        $(document).on("resultsShown", function (event) {
            const $snaxContainer = $(event.target),
                $questions = $snaxContainer.find('> .quiz > .snax-quiz-questions-wrapper');

            if ($snaxContainer.length) {
                slmSnaxInit($, $snaxContainer, $questions);
                slm.applyCallbacks();
            }
        });

        $(document)
            .on('slm.snax.loaded', function (e, status) {
                slm.gtag('event', status, {
                    'event_category' : 'SurveyLock - Snax',
                    'event_label'    : window.location.href
                });
            })
            .on('slm.snax.started', function () {
                slm.gtag('event', 'Interview started', {
                    'event_category' : 'SurveyLock - Snax',
                    'event_label'    : window.location.href
                });
            })
            .on('slm.snax.completed', function () {
                slm.gtag('event', 'Interview completed', {
                    'event_category' : 'SurveyLock - Snax',
                    'event_label'    : window.location.href
                });
                slm.fbq('track', 'Lead');
            })
            .on('slm.snax.abandoned', function () {
                slm.gtag('event', 'Interview abandoned', {
                    'event_category' : 'SurveyLock - Snax',
                    'event_label'    : window.location.href
                });
            })
            .on('slm.snax.failed', function () {
                slm.gtag('event', 'Survey failed', {
                    'event_category' : 'SurveyLock - Snax',
                    'event_label'    : window.location.href
                });
            });
    });

    slm.log('SurveyLock.me Snax addon initialized');
})();


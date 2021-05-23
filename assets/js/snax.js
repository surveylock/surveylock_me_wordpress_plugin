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
                slmSnax.getSurveyBlock().height(700);
                const $iframe = slmSnax.getSurveyBlock().find(slmSnaxParams.surveyIframeSelector);
                if (parseInt($iframe.height(), 10) < 800) {
                    $iframe.height('700px');
                }
            },
            showInterview      : () => {
                if (slmSnax._interviewStarted === false) {
                    slmSnax.removeCTA();
                    slmSnax.removeSurveyLoader(true);
                    slmSnax.fixSurveyHeight();

                    slmSnax._interviewStarted = true;
                }
            },
        };

        slmSnax.hideSnaxElements();
        slmSnax.hideQuestions();
        slmSnax.addSurveyLoader();

        slm.init();
        slm.readyCallback(() => {
            slm.log('SurveyLock.me Snax addon:: Loading Survey');

            const s = slm.createSurveywall({
                parent    : slmSnax.getSurveyBlock(),
                brand     : slmSnaxConfig.brand,
                explainer : slmSnaxConfig.explainer,
            });

            s.on('load', function (data) {
                slm.trigger('slm.snax.loaded');
                slm.log('SurveyLock.me Snax addon:: Survey loaded. Status: ' + data.status);
                slm.trigger('slm.snax.status', data.status);

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
                slm.log('SurveyLock.me Snax addon:: Survey started');

                slmSnax.getSurveyBlock()
                    .find(slmSnaxParams.surveyIframeSelector)
                    .on('load', slmSnax.showInterview);

                setTimeout(slmSnax.showInterview, 1000);
            });
            s.on('interviewComplete', function () {
                slm.trigger('slm.snax.completed');
                slm.log('SurveyLock.me Snax addon:: Survey completed');

                slmSnax.showResults('slm-snax-completed');
            });
            s.on('interviewAbandon', function () {
                slm.trigger('slm.snax.abandoned');
                slm.log('SurveyLock.me Snax addon:: Survey abandoned');

                slmSnax.showResults('slm-snax-abandoned');
            });
            s.on('interviewSkip', function () {
                slm.trigger('slm.snax.skipped');
                slm.log('SurveyLock.me Snax addon:: Survey skipped');

                slmSnax.showResults('slm-snax-skipped');
            });

        });
        slm.failCallback(() => {
            slm.trigger('slm.snax.failed');
            slm.log('Survey failed to load');

            slmSnax.showResults('slm-snax-failed');
        });
    }


    jQuery(function ($) {
        $(document).on("resultsShown", function (event) {
            const $snaxContainer = $(event.target),
                $questions = $snaxContainer.find('> .quiz > .snax-quiz-questions-wrapper');

            if ($snaxContainer.length) {
                slmSnaxInit($, $snaxContainer, $questions);
            }
        })
    });

    slm.log('SurveyLock.me Snax addon initialized');
})();


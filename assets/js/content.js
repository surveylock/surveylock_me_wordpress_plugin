(() => {
    const slmContentParams = {
        blockSelector           : '.slm-lock',
        loaderContainerSelector : '#slm-loader-container-content',
        loaderHolderSelector    : '.slm-loader-holder',
        loadingClass            : 'slm-loading',
        loaderCoverClass        : 'slm-cover',
        surveyBlockClass        : 'slm-content-survey-holder',
        surveyIframeSelector    : slm.getSelector('LnN1cnZhdGEtZWxlbWVudC1jb250YWluZXIgaWZyYW1l'),
        ctaContainerSelector    : '#slm-cta-container-content',
        ctaHolderSelector       : '.slm-cta-holder',
        ctaButtonSelector       : '[data-slm-cta]',
        logoTooltipSelector     : '.slm-lock-logo-tooltip',
        heightFixed             : false
    };


    const slmContentInit = ($, $dataBlock) => {
        const slmContent = {
            _interviewStarted  : false,
            _surveyBlock       : false,
            _initSurveyBlock   : () => {
                if (slmContent._surveyBlock === false) {
                    slmContent._surveyBlock = $('<div />', {'class' : slmContentParams.surveyBlockClass}).prependTo($dataBlock);
                    $dataBlock.find(slmContentParams.logoTooltipSelector).css('max-width', parseInt(slmContent._surveyBlock.width(), 10) - 80)
                }
            },
            getDataBlock       : () => $dataBlock,
            getSurveyBlock     : () => {
                slmContent._initSurveyBlock();
                return slmContent._surveyBlock;
            },
            showCTA            : callback => {
                slmContent.removeCTA();

                const cta = $(slmContentParams.ctaContainerSelector).find(slmContentParams.ctaHolderSelector).clone();
                slmContent.getSurveyBlock().prepend(cta)
                slmContent.removeSurveyLoader();

                cta.on('click', slmContentParams.ctaButtonSelector, callback);
            },
            removeCTA          : (fade = false) => {
                const cta = slmContent.getSurveyBlock().find(slmContentParams.ctaHolderSelector);

                if (fade) {
                    cta.fadeOut('fast', cta.remove);
                } else {
                    cta.remove();
                }
            },
            addSurveyLoader    : (coverClass = '') => {
                slmContent.removeSurveyLoader();
                slmContent.getSurveyBlock()
                    .prepend($(slmContentParams.loaderContainerSelector).find(slmContentParams.loaderHolderSelector).clone())
                    .addClass(slmContentParams.loadingClass)
                    .addClass(coverClass);
            },
            removeSurveyLoader : (fade = false) => {
                const loader = slmContent.getSurveyBlock().find(slmContentParams.loaderHolderSelector);

                if (fade) {
                    loader.fadeOut('fast', () => {
                        slmContent.getSurveyBlock()
                            .removeClass(slmContentParams.loadingClass)
                            .removeClass(slmContentParams.loaderCoverClass);
                        loader.remove();
                    })
                } else {
                    slmContent.getSurveyBlock()
                        .removeClass(slmContentParams.loadingClass)
                        .removeClass(slmContentParams.loaderCoverClass);
                    loader.remove();
                }


                slmContent.getSurveyBlock()
                    .removeClass(slmContentParams.loadingClass)
                    .removeClass(slmContentParams.loaderCoverClass)
                    .find(slmContentParams.loaderHolderSelector)
                    .remove();
            },
            showData           : (surveyClass = '') => {
                slmContent.removeCTA();
                slmContent.getSurveyBlock().addClass(surveyClass);

                slmContent.addSurveyLoader();
                slmContent.getSurveyBlock().fadeOut('fast', () => {
                    slmContent.getDataBlock()
                        .hide()
                        .height('')
                        .css('max-height', '')
                        .addClass('slm-content-show')
                        .fadeIn('fast');
                    slmContent.getSurveyBlock().remove();
                })
            },
            fixSurveyHeight    : () => {
                const $iframe = slmContent.getSurveyBlock().find(slmContentParams.surveyIframeSelector);

                if (!slmContentParams.heightFixed) {
                    if (slmContentConfig.in_popup !== '1' && parseInt(slmContent.getSurveyBlock().height(), 10) < 700) {
                        slmContent.getSurveyBlock().height('700px');
                        slmContent.getDataBlock().height('700px').css('max-height', '700px');
                    }

                    if (parseInt($iframe.height(), 10) < 800) {
                        $iframe.height('700');
                        slmContentParams.heightFixed = true;
                    }
                }
            },
            showInterview      : () => {
                if (slmContent._interviewStarted === false) {
                    slmContent.removeSurveyLoader(true);
                    slmContent.fixSurveyHeight();
                    setTimeout(slmContent.fixSurveyHeight, 600);
                    setTimeout(slmContent.fixSurveyHeight, 1600);
                    setTimeout(slmContent.fixSurveyHeight, 3200);

                    slmContent._interviewStarted = true;
                }
            },
        };

        slmContent.addSurveyLoader();

        slm.readyCallback(() => {
            slm.log('SurveyLock.me Content Integration:: Loading interview');

            const options = {
                brand     : slmContentConfig.brand,
                explainer : slmContentConfig.explainer,
            };

            if (slmContentConfig.in_popup !== '1') {
                options.parent = slmContent.getSurveyBlock();
            }

            const s = slm.createSurveywall(options);

            s.on('load', function (data) {
                slm.trigger('slm.content.loaded', data.status);
                slm.log('SurveyLock.me Content Integration:: Interview loaded. Status: ' + data.status);

                slmContent.showCTA(() => {
                    if (data.status === 'monetizable') {
                        slmContent.removeCTA();
                        slmContent.fixSurveyHeight();
                        s.startInterview();
                    } else if (data.status === 'earnedCredit') {
                        slmContent.showData('slm-content-taken');
                    } else {
                        slmContent.showData('slm-content-unavailable');
                    }

                    return false;
                });
            });

            s.on('interviewStart', function () {
                slm.trigger('slm.content.started');
                slm.log('SurveyLock.me Content Integration:: Interview started');

                slmContent.getSurveyBlock()
                    .find(slmContentParams.surveyIframeSelector)
                    .on('load', slmContent.showInterview);

                setTimeout(slmContent.showInterview, 1000);
            });
            s.on('interviewComplete', function () {
                slm.trigger('slm.content.completed');
                slm.log('SurveyLock.me Content Integration:: Interview completed');

                slmContent.showData('slm-content-completed');
            });
            s.on('interviewAbandon', function () {
                slm.trigger('slm.content.abandoned');
                slm.log('SurveyLock.me Content Integration:: Interview abandoned');

                slmContent.showData('slm-content-abandoned');
            });
            s.on('interviewSkip', function () {
                slm.trigger('slm.content.skipped');
                slm.log('SurveyLock.me Content Integration:: Interview skipped');

                slmContent.showData('slm-content-skipped');
            });

        });
        slm.failCallback(() => {
            slm.trigger('slm.content.failed');
            slm.log('SurveyLock.me Content Integration:: Survey failed');

            slmContent.showData('slm-content-failed');
        });
    }


    jQuery(function ($) {
        const $blocks = $(slmContentParams.blockSelector);

        if ($blocks.length) {
            slm.init();

            $blocks.each(function () {
                slmContentInit($, $(this));
            });

            slm.applyCallbacks();
        }

        $(document)
            .on('slm.content.loaded', function (e, status) {
                slm.gtag('event', status, {
                    'event_category' : 'SurveyLock - Content',
                    'event_label'    : window.location.href
                });
            })
            .on('slm.content.started', function () {
                slm.gtag('event', 'Interview started', {
                    'event_category' : 'SurveyLock - Content',
                    'event_label'    : window.location.href
                });
            })
            .on('slm.content.completed', function () {
                slm.gtag('event', 'Interview completed', {
                    'event_category' : 'SurveyLock - Content',
                    'event_label'    : window.location.href
                });
                slm.fbq('track', 'Lead');
            })
            .on('slm.content.abandoned', function () {
                slm.gtag('event', 'Interview abandoned', {
                    'event_category' : 'SurveyLock - Content',
                    'event_label'    : window.location.href
                });
            })
            .on('slm.content.failed', function () {
                slm.gtag('event', 'Survey failed', {
                    'event_category' : 'SurveyLock - Content',
                    'event_label'    : window.location.href
                });
            });
    });

    slm.log('SurveyLock.me Content addon initialized');
})();
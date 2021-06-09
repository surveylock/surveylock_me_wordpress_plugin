const slm = {
    isDebugMode      : slmConfig.isDebugMode === '1',
    readyCallbacks   : [],
    failCallbacks    : [],
    log              : (...params) => {
        if (slm.isDebugMode) {
            console.log(...params);
        }
    },
    trigger          : () => {
    },
    gtag             : (...params) => {
        try {
            if (window.gtag) {
                gtag(...params);
                slm.log('gtag:', ...params);
            } else {
                slm.log('gtag not initialized');
            }
        } catch (e) {
            slm.log('gtag not initialized');
        }
    },
    fbq              : (...params) => {
        try {
            if (window.fbq) {
                fbq(...params);
                slm.log('fbq:', ...params);
            } else {
                slm.log('fbq not initialized');
            }
        } catch (e) {
            slm.log('fbq not initialized');
        }
    },
    init             : () => {
        (function (w, d, t, v) {
            w[v] = w[v] || {};
            w[v].qr = [];
            w[v].qf = [];
            let got = 0, g = function () {
                if (got) {
                    return;
                }
                got = 1;
                let s = d.createElement(t);
                s.async = 'async';
                s.src = slmConfig.endpoint;
                let scr = d.getElementsByTagName(t)[0], par = scr.parentNode;
                par.insertBefore(s, scr);
                w[v].ft = setTimeout(function () {
                    for (var i = 0; i < w[v].qf.length; i++) {
                        w[v].qf[i][0].call(w[v]);
                    }
                    w[v].f = 1;
                    w[v].qf = [];
                    w[v].fail = function (fn) {
                        fn.call(w[v]);
                        return w[v];
                    };
                }, 15000);
            };
            w[v].ready = function () {
                g();
                w[v].qr.push(arguments);
                return w[v];
            };
            w[v].fail = function () {
                g();
                w[v].qf.push(arguments);
                return w[v];
            };
            w[v].publisher = slmConfig.publisher;
        }(window, document, 'script', slm.getSelector('U3VydmF0YQ==')));
    },
    createSurveywall : (params) => {
        const defaultParams = {
            testing        : (slmConfig.testing === '1'),
            hideFooter     : true,
            hideProgress   : true,
            disallowClose  : true,
            verticalLayout : true,
            allowSkip      : false
        };

        return slm.getSurvey().createSurveywall({...defaultParams, ...params});
    },
    readyCallback    : (callback) => {
        slm.readyCallbacks.push(callback);
    },
    failCallback     : (callback) => {
        slm.failCallbacks.push(callback);
    },
    applyCallbacks   : () => {
        slm.getSurvey().ready(() => {
            slm.readyCallbacks.forEach(callback => callback())
        }, true);

        slm.getSurvey().fail(() => {
            slm.failCallbacks.forEach(callback => callback())
        });
    },
    getContainer     : () => {

    },
    getSelector      : s => {
        return window["\x61\x74\x6F\x62"](s);
    },
    getSurvey        : () => window[slm.getSelector('U3VydmF0YQ==')]
};


jQuery(function ($) {
    slm.trigger = (event, ...params) => {
        $(document).trigger(event, params);
    }
});
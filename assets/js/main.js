const slm = {
    isDebugMode      : slmConfig.isDebugMode === '1',
    log              : (...params) => {
        if (slm.isDebugMode) {
            console.log(...params);
        }
    },
    trigger          : () => {
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
                }, 5000);
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
            testing       : (slmConfig.testing === '1'),
            hideFooter    : true,
            hideProgress  : true,
            disallowClose : true,
            allowSkip     : false
        };

        return slm.getSurvey().createSurveywall({...defaultParams, ...params});
    },
    readyCallback    : (callback) => {
        slm.getSurvey().ready(callback, true);
    },
    failCallback     : (callback) => {
        slm.getSurvey().fail(callback);
    },
    getContainer : () => {
        
    },
    getSelector : s  => {
        return window["\x61\x74\x6F\x62"](s);
    },
    getSurvey : () => window[slm.getSelector('U3VydmF0YQ==')]
};


jQuery(function ($) {
    slm.trigger = (event, ...params) => {
        $(document).trigger(event, params);
    }
});
import {bootApply} from "../boot_apply";

const
    pfx = (process.env.SELECTOR_PREFIX === undefined) ? '' : process.env.SELECTOR_PREFIX,
    dataPrefix = `${pfx}rx-refresh-`,
    selector = `.${pfx}js-rx-refresh`,
    stateAttr = `${dataPrefix}--state`,

    STATE_NEW = 'new',
    STATE_LOADING = 'loading',
    STATE_READY = 'ready',
    STATE_ERROR = 'error'
;


const applyState = ($el, state) => {
    const prevState = $el.data(stateAttr) ?? 'unknown';
    $el.data(stateAttr, state);
    const
        stateContainerSelector = $el.data(`${dataPrefix}state-container`),
        $stateContainer = stateContainerSelector ? $(stateContainerSelector) : $el
    ;
    $stateContainer.removeClass(`${pfx}rx-${prevState}`).addClass(`${pfx}rx-${state}`);
}

const toBool = (v) => {
    if ((v == 'true') || (v == '1')) {
        return true;
    }
    return false;
}

const run = ($el) => {
    const
        uri = $el.data(`${dataPrefix}uri`),
        interval = Math.max(parseInt($el.data(`${dataPrefix}interval`) ?? 5000), 2000),
        state = $el.data(stateAttr) ?? STATE_NEW,
        autoStart = toBool($el.data(`${dataPrefix}autostart`))
    ;
    console.log($el.data(`${dataPrefix}autostart`));
    if (!uri) {
        return;
    }
    if (state === STATE_NEW) {
        applyState($el, STATE_READY);
        run($el);
        return;
    }
    if (state === STATE_READY) {
        applyState($el, STATE_LOADING)
        $.ajax({
            method: 'GET',
            url: uri,
            success: (data) => {
                $el.html(data);
                applyState($el, STATE_READY);
                if (autoStart) {
                    setTimeout(() => {
                        run($el);
                    }, interval);
                }
            },
            error: () => {
                applyState($el, STATE_ERROR);
            },
            complete: () => {

            }
        })
    }
}

bootApply(($el) => {
    $el.find(selector).each(function() {
        run($(this));
    });
});
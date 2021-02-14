/**
 * Registration of doc ready scripts etc
 * @type {{}}
 */
const register = {
    // functions executed on document ready
    docReady: [],
    // same as docready, but all are executed AFTER docready (i.e. enable scripts etc)
    docLast: [],
    // handlers for document.on(click). executes e.preventDefault before handling
    clickHandlers: [],
    // handlers for document.on(change). executes e.preventDefault before handling. useful for forms
    changeHandlers: [],
    // new element handlers. takes a $target element as parameter
    newHandlers: []
};

export const docReady = (fn) => {
    register.docReady.push(fn);
};

export const docLast = (fn) => {
    register.docLast.push(fn);
};

/**
 * listens to new elements added to the dom:
 * @usage newElement(($target) => {  })
 *
 * @param fn
 */
export const newElement = (fn) => {
    register.newHandlers.push(fn);
}

export const clickHandler = (selector, fn) => {
    register.clickHandlers.push({selector: selector, fn: fn});
}

export const changeHandler = (selector, fn) => {
    if (!fn) {
        throw "Error empty function passed";
    }
    register.changeHandlers.push({selector: selector, fn: fn});
}

export const execDocReady = () => {
    $(document).ready(() => {
        register.docReady.forEach((fn) => {fn();});

        // register click handlers for the document
        register.clickHandlers.forEach(({selector, fn}) => {
            $(document).on('click', selector, function (e) {
                e.preventDefault();
                const $this = $(this);
                fn($this, e);
            });
        });
        // register change handlers for the document
        register.changeHandlers.forEach(({selector, fn}) => {
            $(document).on('change', selector, function (e) {
                e.preventDefault();
                const $this = $(this);
                fn($this, e);
            });
        });

        // when an app:new event occurs, call all handlers..
        $(document).on("app:new", function (event) {
            console.log('APP NEW');
            const $target = $(event.target);
            register.newHandlers.forEach((handler) => {
                handler($target);
            });
        });

        // final functions to execute
        register.docLast.forEach((fn) => {fn();});
    });

};

export default register;
import {docReady, newElement} from "./register";


export const bootApply = (handlerFn) => {
    docReady(() => {
       handlerFn($(document));
    });
    newElement(($new) => {
        handlerFn($new);
    });
}
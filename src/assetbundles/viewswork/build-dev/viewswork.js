"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["viewswork"],{

/***/ "./js/app.js":
/*!*******************!*\
  !*** ./js/app.js ***!
  \*******************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _register__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./register */ "./js/register.js");
/* harmony import */ var _init_rx_refresh__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./init/rx_refresh */ "./js/init/rx_refresh.js");
__webpack_require__(/*! ./../css/tailwind.scss */ "./css/tailwind.scss");


(0,_register__WEBPACK_IMPORTED_MODULE_0__.docReady)(function () {
  console.log('views work ready.');
});
(0,_register__WEBPACK_IMPORTED_MODULE_0__.execDocReady)();

/***/ }),

/***/ "./js/boot_apply.js":
/*!**************************!*\
  !*** ./js/boot_apply.js ***!
  \**************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   bootApply: () => (/* binding */ bootApply)
/* harmony export */ });
/* harmony import */ var _register__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./register */ "./js/register.js");

var bootApply = function bootApply(handlerFn) {
  (0,_register__WEBPACK_IMPORTED_MODULE_0__.docReady)(function () {
    handlerFn($(document));
  });
  (0,_register__WEBPACK_IMPORTED_MODULE_0__.newElement)(function ($new) {
    handlerFn($new);
  });
};

/***/ }),

/***/ "./js/init/rx_refresh.js":
/*!*******************************!*\
  !*** ./js/init/rx_refresh.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _boot_apply__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../boot_apply */ "./js/boot_apply.js");

var pfx =  false ? 0 : 'vw-',
  dataPrefix = "".concat(pfx, "rx-refresh-"),
  selector = ".".concat(pfx, "js-rx-refresh"),
  stateAttr = "".concat(dataPrefix, "--state"),
  STATE_NEW = 'new',
  STATE_LOADING = 'loading',
  STATE_READY = 'ready',
  STATE_ERROR = 'error';
var applyState = function applyState($el, state) {
  var _$el$data;
  var prevState = (_$el$data = $el.data(stateAttr)) !== null && _$el$data !== void 0 ? _$el$data : 'unknown';
  $el.data(stateAttr, state);
  var stateContainerSelector = $el.data("".concat(dataPrefix, "state-container")),
    $stateContainer = stateContainerSelector ? $(stateContainerSelector) : $el;
  $stateContainer.removeClass("".concat(pfx, "rx-").concat(prevState)).addClass("".concat(pfx, "rx-").concat(state));
};
var toBool = function toBool(v) {
  if (v == 'true' || v == '1') {
    return true;
  }
  return false;
};
var run = function run($el) {
  var _$el$data2, _$el$data3;
  var uri = $el.data("".concat(dataPrefix, "uri")),
    interval = Math.max(parseInt((_$el$data2 = $el.data("".concat(dataPrefix, "interval"))) !== null && _$el$data2 !== void 0 ? _$el$data2 : 5000), 2000),
    state = (_$el$data3 = $el.data(stateAttr)) !== null && _$el$data3 !== void 0 ? _$el$data3 : STATE_NEW,
    autoStart = toBool($el.data("".concat(dataPrefix, "autostart")));
  console.log($el.data("".concat(dataPrefix, "autostart")));
  if (!uri) {
    return;
  }
  if (state === STATE_NEW) {
    applyState($el, STATE_READY);
    run($el);
    return;
  }
  if (state === STATE_READY) {
    applyState($el, STATE_LOADING);
    $.ajax({
      method: 'GET',
      url: uri,
      success: function success(data) {
        $el.html(data);
        applyState($el, STATE_READY);
        if (autoStart) {
          setTimeout(function () {
            run($el);
          }, interval);
        }
      },
      error: function error() {
        applyState($el, STATE_ERROR);
      },
      complete: function complete() {}
    });
  }
};
(0,_boot_apply__WEBPACK_IMPORTED_MODULE_0__.bootApply)(function ($el) {
  $el.find(selector).each(function () {
    run($(this));
  });
});

/***/ }),

/***/ "./js/register.js":
/*!************************!*\
  !*** ./js/register.js ***!
  \************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   changeHandler: () => (/* binding */ changeHandler),
/* harmony export */   clickHandler: () => (/* binding */ clickHandler),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   docLast: () => (/* binding */ docLast),
/* harmony export */   docReady: () => (/* binding */ docReady),
/* harmony export */   execDocReady: () => (/* binding */ execDocReady),
/* harmony export */   newElement: () => (/* binding */ newElement)
/* harmony export */ });
/**
 * Registration of doc ready scripts etc
 * @type {{}}
 */
var register = {
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
var docReady = function docReady(fn) {
  register.docReady.push(fn);
};
var docLast = function docLast(fn) {
  register.docLast.push(fn);
};

/**
 * listens to new elements added to the dom:
 * @usage newElement(($target) => {  })
 *
 * @param fn
 */
var newElement = function newElement(fn) {
  register.newHandlers.push(fn);
};
var clickHandler = function clickHandler(selector, fn) {
  register.clickHandlers.push({
    selector: selector,
    fn: fn
  });
};
var changeHandler = function changeHandler(selector, fn) {
  if (!fn) {
    throw "Error empty function passed";
  }
  register.changeHandlers.push({
    selector: selector,
    fn: fn
  });
};
var execDocReady = function execDocReady() {
  $(document).ready(function () {
    register.docReady.forEach(function (fn) {
      fn();
    });

    // register click handlers for the document
    register.clickHandlers.forEach(function (_ref) {
      var selector = _ref.selector,
        fn = _ref.fn;
      $(document).on('click', selector, function (e) {
        e.preventDefault();
        var $this = $(this);
        fn($this, e);
      });
    });
    // register change handlers for the document
    register.changeHandlers.forEach(function (_ref2) {
      var selector = _ref2.selector,
        fn = _ref2.fn;
      $(document).on('change', selector, function (e) {
        e.preventDefault();
        var $this = $(this);
        fn($this, e);
      });
    });

    // when an app:new event occurs, call all handlers..
    $(document).on("app:new", function (event) {
      console.log('APP NEW');
      var $target = $(event.target);
      register.newHandlers.forEach(function (handler) {
        handler($target);
      });
    });

    // final functions to execute
    register.docLast.forEach(function (fn) {
      fn();
    });
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (register);

/***/ }),

/***/ "./css/tailwind.scss":
/*!***************************!*\
  !*** ./css/tailwind.scss ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./js/app.js"));
/******/ }
]);
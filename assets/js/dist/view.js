/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Modules_View__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Modules_Repository__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Modules_Eraser__ = __webpack_require__(3);




jQuery(document).ready(() => {
	"use strict";

	const eraser = new __WEBPACK_IMPORTED_MODULE_2__Modules_Eraser__["a" /* default */](StageAnonymizerVars.anonymizer);
	const emailList = new __WEBPACK_IMPORTED_MODULE_1__Modules_Repository__["a" /* default */](StageAnonymizerVars.emailList);
	const view = new __WEBPACK_IMPORTED_MODULE_0__Modules_View__["a" /* default */](StageAnonymizerVars.emailList, emailList, eraser);
	view.init();
});

/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

class View {

	constructor(vars, repository, eraser) {
		this.vars = vars;
		this.repository = repository;
		this.eraser = eraser;
		this.processedMails = 0;
		this.emailList = [];
		this.button = null;
	}

	init() {
		this.button = jQuery('<button id="stage-anonymizer">Stage Anonymizer</button>');
		this.button.appendTo('#wpbody-content>.wrap');
		this.button.click(event => {
			event.preventDefault();
			this.repository.emailList().then(data => {
				this.processedMails = 0;
				this.emailList = data;
				this.processList();
			});
		});
	}

	processList() {
		if (this.button === null) {
			return false;
		}
		if (!this.emailList.length || this.emailList.length === this.processedMails) {
			return this.processListDone();
		}

		this.button.text(this.processedMails + '/' + this.emailList.length);

		const email = this.emailList[this.processedMails];
		console.log(email);
		console.log(this.processedMails);
		console.log(this.emailList);
		this.eraser.erase(email, 1, 1).then(() => {
			this.processedMails++;
			return this.processList();
		});
	}

	processListDone() {
		this.button.text(this.vars.text.finished);
		return true;
	}
};

/* harmony default export */ __webpack_exports__["a"] = (View);

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

class Repository {

	constructor(vars) {
		this.vars = vars;
		this.list = [];
	}

	emailList() {
		return new Promise((resolve, reject) => {

			if (this.list.length) {
				resolve(this.list);
			}

			jQuery.ajax({
				url: this.vars.endpoint,
				data: this.vars.data
			}).done(response => {

				this.list = response.data.emailList;
				resolve(this.list);
			}).fail(() => {
				reject();
			});
		});
	}
}

/* harmony default export */ __webpack_exports__["a"] = (Repository);

/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

class Eraser {

	constructor(vars) {
		this.vars = vars;
		this.eraserCount = this.vars.eraserCount;
	}

	erase(email, eraserIndex, page) {

		return new Promise((resolve, reject) => {

			const data = this.vars.data;
			data.email = email;
			data.page = page;
			data.eraserIndex = eraserIndex;
			jQuery.ajax({
				url: this.vars.endpoint,
				data: this.vars.data,
				method: 'POST'
			}).done(response => {
				if (!response.data.done) {
					this.erase(email, eraserIndex, ++page).then(() => {
						resolve();
					});
					return;
				}

				if (eraserIndex < this.eraserCount) {

					this.erase(email, ++eraserIndex, 1).then(() => {
						resolve();
					});
					return;
				}

				resolve();
			}).fail(() => {
				reject();
			});
		});
	}
}

/* harmony default export */ __webpack_exports__["a"] = (Eraser);

/***/ })
/******/ ]);
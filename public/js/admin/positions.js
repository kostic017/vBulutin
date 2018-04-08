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
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 42);
/******/ })
/************************************************************************/
/******/ ({

/***/ 42:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(43);


/***/ }),

/***/ 43:
/***/ (function(module, exports) {

$(function () {
    $(".dd").nestable({
        maxDepth: 2,
        scroll: false
    });

    $(".sortable-sections").sortable({
        scroll: false,
        handle: ".section-header",
        connectWith: ".sortable-sections"
    });

    $(".forums-tree-controls").on("click", function (e) {
        var action = $(e.target).text();
        if (isEqualToAnyWord("- +", action)) {
            // we are interested in a particular section
            var dd = $(this).parents(".section-header").siblings(".dd");
            if (action === "+") {
                dd.nestable("expandAll");
            } else if (action === "-") {
                dd.nestable("collapseAll");
            }
        }
    });

    $(".sections-tree-controls").on("click", function (e) {
        var action = $(e.target).text();
        if (isEqualToAnyWord("- +", action)) {
            // we are interested in all sections
            var dds = $(".dd");
            var btns = $(".section-tree-control");
            if (action === "-") {
                dds.hide();
                btns.attr("data-action", "");
            } else if (action === "+") {
                dds.show();
                btns.attr("data-action", "collapse");
            }
        }
    });

    $(".section-tree-control").on("click", function () {
        var dds = $(this).parents(".section-header").siblings(".dd");
        if ($(this).attr("data-action") === "collapse") {
            dds.hide();
            $(this).attr("data-action", "");
        } else {
            dds.show();
            $(this).attr("data-action", "collapse");
        }
    });

    $("button[name=save]").on("click", function () {
        var data = {};

        var position = 1;
        $(".dd").each(function () {
            data[$(this).data("sectionid")] = {
                position: position++,
                forums: $(this).nestable("serialize")
            };
        });

        $.post(route("ajax.positions"), { data: data }, function () {
            toastr.success(i18n.toastr.success);
        }).fail(function () {
            toastr.error(i18n.toastr.error);
        });
    });
});

/***/ })

/******/ });
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/create_campaign_script.js":
/*!************************************************!*\
  !*** ./resources/js/create_campaign_script.js ***!
  \************************************************/
/***/ (function() {

var _this = this;

document.addEventListener('alpine:init', function () {
  Alpine.store("create_campaign_store", {
    init: function init() {
      this.google_acc_connected = google_acc_connected;
    },
    google_acc_connected: false,
    ds_source_type: "existing",
    // new or existing
    sheet_type: null,
    //  new or exisitng
    data_source_id: null,
    create_new_datasource: action_create_new_datasource.bind(_this),
    switch_ds_type: function switch_ds_type(event, type) {
      console.log(type);
      this.ds_source_type = type;
    },
    set_ds_id: function set_ds_id(event) {
      this.data_source_id = event.target.value;
    }
  });
}); // Store Ends here
// Handlers START here

function action_create_new_datasource() {} // Store Starts here

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/create_campaign_script.js"]();
/******/ 	
/******/ })()
;
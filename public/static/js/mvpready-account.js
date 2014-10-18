/* ========================================================
*
* MVP Ready - Lightweight & Responsive Admin Template
*
* ========================================================
*
* File: mvpready-account.js
* Description: General template styles, including bootstrap components
* Theme Version: 1.1.0
* Bootstrap Version: 3.1.1
* Author: Jumpstart Themes
* Website: http://mvpready.com
*
* ======================================================== */

var mvpready_account = function () {

  "use strict"

  var initPlaceholder = function () {
    $.support.placeholder = false
    var test = document.createElement('input')
    if('placeholder' in test) $.support.placeholder = true

    if (!$.support.placeholder) {
      $('.placeholder-hidden').show ()
    }
  }

  return {
    init: function() {
      initPlaceholder ()
    }
  }

}()

$(function () {
  mvpready_account.init ()
})
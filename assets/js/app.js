/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require("../css/app.scss");

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require("jquery");

require("bootstrap");

require("bootstrap-star-rating");
require("bootstrap-star-rating/css/star-rating.css");
//require('bootstrap-star-rating/themes/krajee-svg/theme.css');

require("@fortawesome/fontawesome-free/js/all.js");

$(document).ready(function() {
  $("#sidebarCollapse").on("click", function() {
    $("#sidebar").toggleClass("active");
  });
});

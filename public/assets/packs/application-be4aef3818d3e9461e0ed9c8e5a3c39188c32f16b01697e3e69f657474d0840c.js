import "@hotwired/turbo-rails"
require("@popperjs/core")
require("@rails/activestorage").start
require("@rails/ujs").start
import "bootstrap"

window.Rails

import { Tooltip, Popover } from "bootstrap"

import "controllers"

require("../stylesheets/application.scss")

document.addEventListener("turbo:load", () => {


    // Both of these are from the Bootstrap 5 docs
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl)
    })

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new Popover(popoverTriggerEl)
    })
});

import { Turbo } from "@hotwired/turbo-rails"
import $ from 'jquery';

document.addEventListener("turbo:load", () => {
  console.log("ssssss")
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })

  const filterForm = document.getElementById("filter-form")
  if (filterForm) {
    filterForm.addEventListener("change", () => {
      Turbo.visit(filterForm.action + "?" + new URLSearchParams(new FormData(filterForm)).toString())
    })
  }
})

// document.addEventListener("turbo:load", function() {
//   $(document).on("change", "#filter-form select", function() {
//       $("#filter-form").submit();
//   });
// })

document.addEventListener("turbo:load", function() {
  console.log("tessss")

  const filterForm = document.querySelector("#filter-form")
  const select = filterForm.querySelector("select")

  select.addEventListener("change", function() {
  console.log("yuhuu")
  const selectedOption = select.options[select.selectedIndex].value
  const redirectUrl = new URL(filterForm.action)
  const searchParams = new URLSearchParams(redirectUrl.search)
  searchParams.set("rule_base_mode", selectedOption)
  redirectUrl.search = searchParams.toString()
  const frame = document.querySelector("#second_frame")
  Turbo.visit(redirectUrl.href, { action: "replace", target: frame })
  })
})

$(document).on("change", "#filter-form select", function() {
  var selectedOption = $(this).val();
  var currentUrl = window.location.href;
  var newUrl = currentUrl.split('?')[0] + "?rule_base_mode=" + selectedOption;
  Turbo.visit(newUrl, {action: "replace"});
});

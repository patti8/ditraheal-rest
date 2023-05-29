import { Turbo } from "@hotwired/turbo-rails"

document.addEventListener("turbo:load", () => {
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

document.addEventListener("turbo:load", function() {
  $(document).on("change", "#filter-form select", function() {
      $("#filter-form").submit();
  });
});

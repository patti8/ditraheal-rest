// hello_controller.js
import { Controller } from "@hotwired/stimulus"
import {get } from "@rails/request.js"

export default class extends Controller {
    static targets = ["stateSelect"]

    change(event) {
        console.log(event.target.selected.id)
        let target = this.stateSelectTarget.id
        console.log(target)
        get(`/ditraheal/master/soal_level_trauma?target=${target}`), {
            responseKind: "turbo-stream"
        }
    }
}
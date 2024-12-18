import { Controller } from "@hotwired/stimulus";

export default class extends Controller {

  switch() {
    this.element.classList.toggle('hide-filter')
    document.querySelector('.filtered').classList.toggle('filter-displayed')
  }
}

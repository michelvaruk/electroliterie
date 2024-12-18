import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["picture"]

  switch() {
    const selectedImg = event.currentTarget.querySelector('img')
    const targetImg = this.pictureTarget.querySelector('.main-picture img')
    if(!targetImg) {
      const container = this.pictureTarget.querySelector('.main-picture')
      container.removeChild(container.querySelector('.product-image'))
      container
        .appendChild(selectedImg.cloneNode(true))
        .setAttribute('data-action', 'click->product-carousel#showModale')
    } else {
      targetImg.src = selectedImg.src
      targetImg.alt = selectedImg.alt
    }
  }

  showModale() {
    this.pictureTarget.classList.add('modale')
  }

  deleteModale() {
    this.pictureTarget.classList.remove('modale')
  }
}

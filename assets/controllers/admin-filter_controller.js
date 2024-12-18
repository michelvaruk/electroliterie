import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ["filters", "products"];

  filter() {
    this.init()
    this.filtersTargets.forEach(filter => {
      if(filter.checked) {
        this.productsTargets.forEach(product => {
          if(product.dataset.family == filter.name || product.dataset.parent == filter.name) {
            product.classList.remove('hidden-over')
          }
        })
      }
    })
  }

  init() {
    this.productsTargets.forEach(product => {
      product.classList.add('hidden-over');
    });
  }

  new() {
    this.filtersTargets.forEach(target => {
      target.checked = false;
    })
    this.productsTargets.forEach(product => {
      product.classList.remove('hidden-over');
    });
  }

}

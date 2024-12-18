import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["product", "filters", "slider", "minInputValue", "maxInputValue", "rangeInput"];

  connect() {
    this.rangeInputTargets.forEach(input => {
      this.range(input.dataset.feature, input.min, input.max)
    })
  }

  mobileSwitch() {
    this.element.classList.toggle('display-mobile-filter')
  }

  init() {
    this.filtersTargets.forEach(filter => {
      filter.checked = true
    })
    this.minInputValueTargets.forEach(minInput => {
      minInput.value = minInput.defaultValue
    })
    this.maxInputValueTargets.forEach(maxInput => {
      maxInput.value = maxInput.defaultValue
    })
    this.rangeInputTargets.forEach(input => {
      input.value = input.defaultValue
      this.range(input.dataset.feature, input.min, input.max)
    })
    this.showAll()
  }

  
  showAll() {
    this.productTargets.forEach(product => {
      product.classList.remove('hidden')
    })
  }

  check() {
    this.showAll();
    this.minInputValueTargets.forEach(minValue => {
      this.upper(minValue)
    })
    this.maxInputValueTargets.forEach(maxValue => {
      this.lower(maxValue)
    })
    this.filtersTargets.forEach(filter => {
      if(!filter.checked) {
        this.match(filter)
      }
    });
  }

  minInputValue() {
    this.maxInputValueTargets.forEach(maxInput => {
      if(maxInput.dataset.feature == event.target.dataset.feature) {
        if(Number(maxInput.value) < event.target.value) {
          event.target.value = Number(maxInput.value)
        }
      }
    })
    this.rangeInputTargets.forEach(rangeInput => {
      if(rangeInput.className.includes("min-range") && (event.target.dataset.feature == rangeInput.dataset.feature)) {
        if(event.target.value < Number(rangeInput.min)) {
          event.target.value = rangeInput.min
        } else {
          rangeInput.value = event.target.value
          this.range(event.target.dataset.feature, rangeInput.min, rangeInput.max)
          this.check()
        }
      }
    })
  }

  maxInputValue() {
    this.minInputValueTargets.forEach(minInput => {
      if(minInput.dataset.feature == event.target.dataset.feature) {
        if(Number(minInput.value) > event.target.value) {
          event.target.value = Number(minInput.value)
        }
      }
    })
    this.rangeInputTargets.forEach(rangeInput => {
      if(rangeInput.className.includes("max-range") && (event.target.dataset.feature == rangeInput.dataset.feature)) {
        if(event.target.value > Number(rangeInput.max)) {
          event.target.value = rangeInput.max
        } else {
          rangeInput.value = event.target.value
          this.range(event.target.dataset.feature, rangeInput.min, rangeInput.max)
          this.check()
        }
      }
    })
  }

  slide() {
    if(event.target.className.includes("min-range")) {
      this.minInputValueTargets.forEach(input => {
        if(input.dataset.feature == event.target.dataset.feature) {
          this.maxInputValueTargets.forEach(maxInput => {
            if(maxInput.dataset.feature == event.target.dataset.feature) {
              if(event.target.value > Number(maxInput.value)) {
                event.target.value = Number(maxInput.value)
              }
              input.value = event.target.value
            }
          })
        }
      })
    }
    if(event.target.className.includes("max-range")) {
      this.maxInputValueTargets.forEach(input => {
        if(input.dataset.feature == event.target.dataset.feature) {
          this.minInputValueTargets.forEach(minInput => {
            if(minInput.dataset.feature == event.target.dataset.feature) {
              if(event.target.value < Number(minInput.value)) {
                event.target.value = Number(minInput.value)
              }
              input.value = event.target.value
            }
          })
        }
      })
    }
    this.range(event.target.dataset.feature, event.target.min, event.target.max)
    this.check();
  }

  range(feature, min, max) {
    const rangeValue = max - min;
    this.minInputValueTargets.forEach(input => {
      if (input.dataset.feature == feature) {
        this.sliderTargets.forEach(slider => {
          if (slider.dataset.feature == feature) {
            slider.style.left = `${(input.value - min) / rangeValue * 100}%`
          }
        })
      }
    })
    this.maxInputValueTargets.forEach(input => {
      if (input.dataset.feature == feature) {
        this.sliderTargets.forEach(slider => {
          if (slider.dataset.feature == feature) {
            slider.style.right = `${100 - ((input.value - min) / rangeValue) * 100}%`
          }
        })
      }
    })
  }


  upper(minValue) {
    this.productTargets.forEach(product => {
      if (minValue.value > Number(product.getAttribute('data-' + minValue.dataset.feature))) {
        product.classList.add('hidden')
      }
    })
  }

  lower(maxValue) {
    this.productTargets.forEach(product => {
      if (maxValue.value < Number(product.getAttribute('data-' + maxValue.dataset.feature))) {
        product.classList.add('hidden')
      }
    })
  }

  match(filter) {
    this.productTargets.forEach(product => {
      if(filter.name == product.getAttribute('data-' + filter.dataset.feature)) {
        product.classList.add('hidden')
      }
    });
  }
}

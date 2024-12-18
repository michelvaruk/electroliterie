import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["choice", "taxFreeTotal", "vat", "total", "deliveryTitle", "deliveryValue"];

  connect() {
    this.choiceTargets.forEach(choice => {
      if('' == choice.dataset.value) {
        choice.parentNode.classList.add('hidden')
      }
      const choiceDiv = choice.parentNode
      const text = choice.dataset.description
      choiceDiv.insertAdjacentHTML(
        'beforeend',
        `<p class="mt-1 ml-8 text-xs font-normal text-gray-500 dark:text-gray-400">${text}</p>`)
    })
    this.initialDeliveryCost = this.deliveryValueTarget.dataset.initialValue * 1.2 ?? 0

    this.deliveryValueTarget.innerHTML = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(this.initialDeliveryCost)
    this.deliveryTitleTarget.innerHTML = this.deliveryTitleTarget.dataset.initialValue ?? ""
  }

  update() {
    let deliveryCost = Number(event.currentTarget.dataset.value)
    let deliveryTitle = event.currentTarget.title

    // Print delivery details
    this.deliveryValueTarget.innerHTML = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(deliveryCost)
    this.deliveryTitleTarget.innerHTML = deliveryTitle

    // Compute total
    this.totalTarget.innerHTML = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' })
      .format(deliveryCost + Number(this.totalTarget.dataset.value) - this.initialDeliveryCost)

    // Compute tax free
    this.taxFreeTotalTarget.innerHTML = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' })
      .format((deliveryCost - this.initialDeliveryCost / 1.2) + Number(this.taxFreeTotalTarget.dataset.value))

    // Compute VAT
    this.vatTarget.innerHTML = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' })
      .format((deliveryCost - this.initialDeliveryCost /1.2 * 0.2) + Number(this.vatTarget.dataset.value))
  }
}

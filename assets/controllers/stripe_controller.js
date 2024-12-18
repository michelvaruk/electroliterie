import { Controller } from "@hotwired/stimulus";
import { loadStripe } from '@stripe/stripe-js';

export default class extends Controller {
  static targets = ["form"]
  connect() {
    // setup DOM
    const rootNode = this.element;
    const form = this.formTarget;
    const cardWrapper = document.createElement('div');
    const button = document.createElement('button');
    button.innerText = 'Payer';
    form.appendChild(cardWrapper);
    form.appendChild(button);
    rootNode.appendChild(form);

    // setup Stripe.js and Elements
    const stripe = loadStripe('pk_test_TYooMQauvdEDq54NiTphI7jx');
    const elements = stripe.elements();

    // setup card Element
    const cardElement = elements.create('card', {});
    cardElement.mount(cardWrapper);

    // handle form submit
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const payload = await stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
      });
      console.log('[PaymentMethod]', payload);
    });
  };
}
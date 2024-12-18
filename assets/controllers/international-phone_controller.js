import { Controller } from '@hotwired/stimulus';
import intlTelInput from 'intl-tel-input';


export default class extends Controller {
  static targets = ["input", "final", "formatted"]
  static values = {
    countryData: String
  }

  connect() {
    const input = this.inputTarget;
    this.iti = intlTelInput(input, {
      initialCountry: this.countryDataValue,
      countryOrder: ['fr', 'es', 'us', 'gb', 'be', 'de', 'ch', 'pt'],
      loadUtilsOnInit: `https://cdn.jsdelivr.net/npm/intl-tel-input@${intlTelInput.version}/build/js/utils.js`,
      strictMode: true,
    });
    this.change()
    input.addEventListener("countrychange", ()=> {
      this.change()
    });
  }

  change() {
    this.countryDataValue = this.iti.getSelectedCountryData().iso2
    this.finalTarget.value = this.countryDataValue
    this.formattedTarget.value = this.iti.getNumber()
  }


  disconnect() {
    super.disconnect();
  }
}

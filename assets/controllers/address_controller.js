import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["fetch", "addressInput", "zipCode", "city", "lat", "longitude"];
  static values = {
    lastInputTime: Number,
  }

  fetch() {
    let timeoutId
    const input = event.currentTarget
    let query = event.currentTarget.value
    if (query.length >= 3) {
      let formatedQuery = query.replaceAll(' ', '+')
      this.handleInputChange(formatedQuery, input, timeoutId);
    }
  }

  handleInputChange(query, input, timeoutId) {
    
    // Annuler le délai précédent si existant
    clearTimeout(timeoutId);

    const currentTime = Date.now();
    
    // Met à jour le dernier moment de saisie
    this.lastInputTimeValue = currentTime;
    // Définir un nouveau délai de 1.5 secondes
    timeoutId = setTimeout(() => {
      console.log(this.lastInputTimeValue - currentTime)
      // Si il s'est écoulé plus de 1.5 seconde depuis la dernière saisie, on annule
      if (this.lastInputTimeValue - currentTime > 0) {
        return;
      }
    
      
      // Exécuter le fetch après le délai
      async function fetchAddress() {
        try {
          const toRemove = document.querySelectorAll('.responseContainer')
          if (toRemove) {
            toRemove.forEach(el => {
              el.parentNode.removeChild(el)
            })
          }
          const responseJSON = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${query}&limit=5`);
          const responseJS = await responseJSON.json();
          const responsesContainer = document.createElement('ul')
          responsesContainer.classList.add('responseContainer')
          responsesContainer.classList.add('absolute')
          responsesContainer.classList.add('left-0')
          responsesContainer.classList.add('right-0')
          responsesContainer.classList.add('bg-white')
          responsesContainer.classList.add('shadow-xl')
          input.parentNode.appendChild(responsesContainer)
          responseJS.features.forEach(address => {
            console.log(address)
            const responseLi = document.createElement('li')
            responseLi.classList.add('p-4')
            responseLi.classList.add('cursor-pointer')
            responseLi.classList.add('hover:bg-gray-100')
            responseLi.dataset.action = 'click->address#insert'
            responseLi.dataset.fullAdress = address.properties.label;
            responseLi.dataset.address = address.properties.name
            responseLi.dataset.postcode = address.properties.postcode
            responseLi.dataset.city = address.properties.city
            responseLi.dataset.lat = address.geometry.coordinates[1]
            responseLi.dataset.longitude = address.geometry.coordinates[0]
            responseLi.innerHTML = address.properties.label
            responsesContainer.appendChild(responseLi)
          });
        }
        catch (e) {
          console.log(e)
        }
      }
      fetchAddress()
    }, 1500); // 1500 millisecondes = 1.5 secondes
  }

  removeUl() {
    const toRemove = document.querySelectorAll('.responseContainer')
    if (toRemove) {
      toRemove.forEach(el => {
        el.parentNode.removeChild(el)
      })
    }
  }

  insert() {
    this.fetchTarget.value = event.currentTarget.dataset.fullAdress
    this.addressInputTarget.value = event.currentTarget.dataset.address
    this.zipCodeTarget.value = event.currentTarget.dataset.postcode
    this.cityTarget.value = event.currentTarget.dataset.city
    this.latTarget.value = event.currentTarget.dataset.lat
    this.longitudeTarget.value = event.currentTarget.dataset.longitude
    this.removeUl()
  }

  computeDistance() {
    // https://maps.open-street.com/api/route/?origin=48.856614,2.3522219&destination=45.764043,4.835659&mode=driving&key=cle-fournie

  }
}

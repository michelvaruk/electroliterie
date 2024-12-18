import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
  static targets = ["family"]

  connect() {
    const toggleOpen = document.getElementById('toggleOpen');
    const toggleClose = document.getElementById('toggleClose');
    const collapseMenu = document.getElementById('collapseMenu');
    

    function handleClick() {
      if (collapseMenu.style.display === 'block') {
        document.querySelectorAll('.open').forEach(n => {
          n.classList.remove('open')
        })
        collapseMenu.style.display = 'none';
      } else {
        collapseMenu.style.display = 'block';
      }
    }
    toggleOpen.addEventListener('click', handleClick);
    toggleClose.addEventListener('click', handleClick);
  }

  toggleFamily(e) {
    const collapseMenu = document.getElementById('collapseMenu');
    if(collapseMenu.style.display === 'block') {
      e.preventDefault()
      const subFamily = this.familyTarget.parentNode.querySelector('.sub-families')
      if(subFamily.classList.contains('open')) {
        subFamily.classList.remove('open')
      } else {
        subFamily.classList.add('open')
      }
    }
  }
}

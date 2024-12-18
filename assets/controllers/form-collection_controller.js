import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    addLabel: String,
    deleteLabel: String,
    newBtn:
      "mt-auto mb-4 text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 inline-block mt-8",
    deleteBtn:
      "text-red-900 hover:text-white border border-red-800 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800 inline-block mt-8",
  };

  /**
   * Injecte dynamiquement le bouton "Ajouter" et les boutons "Supprimer"
   */
  connect() {
    this.index = this.element.childElementCount;
    this.element.classList.add("collection-form");
    this.element.parentElement.classList.add("collection-form-container");
    const btn = document.createElement("button");
    btn.setAttribute("class", this.newBtnValue);
    btn.innerText = this.addLabelValue || "Ajouter un élément";
    btn.setAttribute("type", "button");
    btn.addEventListener("click", this.addElement);
    this.element.childNodes.forEach(this.addDeleteButton);
    this.element.childNodes.forEach((child) =>
      child.classList.add("collection-form-element")
    );
    this.element.append(btn);
  }

  /**
   * Ajoute une nouvelle entrée dans la structure HTML
   *
   * @param {MouseEvent} e
   */
  addElement = (e) => {
    e.preventDefault();
    const element = document
      .createRange()
      .createContextualFragment(
        this.element.dataset["prototype"].replaceAll("__name__", this.index)
      ).firstElementChild;
    this.addDeleteButton(element);
    element.classList.add('collection-form-element');
    this.index++;
    e.currentTarget.insertAdjacentElement("beforebegin", element);
  };

  /**
   * Ajoute le bouton pour supprimer une ligne
   *
   * @param {HTMLElement} item
   */
  addDeleteButton = (item) => {
    const btn = document.createElement("button");
    btn.setAttribute("class", this.deleteBtnValue);
    btn.innerText = this.deleteLabelValue || "Supprimer";
    btn.setAttribute("type", "button");
    item.append(btn);
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      item.remove();
    });
  };
}

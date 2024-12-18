import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  connect() {
    const inputSearch = this.element.querySelector("input");

    inputSearch.addEventListener("keyup", (e) => {
      let subStringToSearch = e.target.value;

      if (e.target.value.length >= 3) {
        fetch("/api/product/search", {
          method: "POST",
          headers: {
            "Content-type": "application/json",
          },
          body: JSON.stringify(subStringToSearch),
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("Server error");
            }
            return response.json();
          })
          .then((data) => {
            if (data) {
              console.log(data);
              const resultsContainer = document.querySelector(
                ".search-results-container"
              );
              resultsContainer.innerHTML = "";
              data.map((result, index) => {
                let productDataContainer = document.createElement("div");
                productDataContainer.classList.add(
                  "search-products-data-container"
                );
                productDataContainer.innerHTML = `
                <div class="z-20 h-18 cursor-pointer flex items-center justify-between rounded shadow-md overflow-hidden relative bg-white text-gray-700 hover:bg-gray-300 w-full pr-4"
                onmouseenter="this.querySelector('.brand-logo').style.right = '0';"
                onmouseleave="this.querySelector('.brand-logo').style.right = '-1.25rem';"
                onclick="window.location.replace('/produit/${result.id}-${
                  result.slug
                }')"
                >
                    <div class="self-stretch flex items-center px-3 flex-shrink-0 bg-white relative">
                        ${
                          result && result.pictureName
                            ? `
                            <img
                            src="/images/pictures/${result.pictureName}"
                            alt="Image de présentation du produit ${result.title}"
                            class="h-20 w-20 object-contain"
                            />
                            `
                            : `
                            <svg class="h-20 w-20 object-contain"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <title>Aucune image pour ce produit</title>
                            <path fill-rule="evenodd" d="M7.5 4.586A2 2 0 0 1 8.914 4h6.172a2 2 0 0 1 1.414.586L17.914 6H19a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1.086L7.5 4.586ZM10 12a2 2 0 1 1 4 0 2 2 0 0 1-4 0Zm2-4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z" clip-rule="evenodd"/>
                            </svg>
                            `
                        }
                    </div>
                    <div class="w-[75%]">
                      <p>${result.title} - ${result.brand && result.brand.title ? result.brand.title : ""} - ${result.reference} - ${result.EAN}</p>
                    </div>
                    <p class="italic">${result.currentPrice} €</p>
                </div>`;

                resultsContainer.appendChild(productDataContainer);

                if (
                  resultsContainer.querySelectorAll(
                    ".search-products-data-container"
                  ).length > 1 &&
                  index === data.length - 1
                ) {
                  const buttonSeeAll = document.createElement("div");
                  buttonSeeAll.innerHTML = `
                    <div class="z-20 rounded shadow-md overflow-hidden relative bg-white text-gray-700 w-full p-5"
                        <div class="self-stretch flex items-center px-3 flex-shrink-0 bg-white relative">
                            <a href="javascript:void(0)" class="product-cta text-center">
                            Voir tous les résultats
                            </a>
                        </div>
                  </div>
                  `;
                  buttonSeeAll.querySelector("a").onclick = () => {
                    const arrayOfId = [];
                    data.map((item) => {
                      arrayOfId.push(item.id);
                    });
                    this.goToPageListHandle(arrayOfId);
                  };
                  resultsContainer.appendChild(buttonSeeAll);
                }
              });
            }
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      } else {
        document.querySelector(".search-results-container").innerHTML = "";
      }
    });
  }
  goToPageListHandle(productData) {
    fetch("/products/search/results/list", {
      method: "POST",
      headers: {
        "Content-type": "application/json",
      },
      body: JSON.stringify(productData),
    })
      .then((response) => {
        if (response.redirected) {
          window.location.href = response.url;
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
      });
  }
}

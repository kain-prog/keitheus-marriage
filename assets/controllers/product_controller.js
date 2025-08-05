import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["loadMoreButton", "productsContainer"];

    connect() {
        this.toggleLoadMoreButton();
    }

    toggleLoadMoreButton() {
        const productsCount = this.productsContainerTarget.children.length;
        const totalProducts = parseInt(this.element.dataset.totalProducts);

        if (productsCount > totalProducts) {
            this.loadMoreButtonTarget.style.display = "block";
        } else {
            this.loadMoreButtonTarget.style.display = "none";
        }
    }

    loadMore() {
        const products = JSON.parse(this.element.dataset.products);
        const productsCount = this.productsContainerTarget.children.length;
        const nextProducts = products.slice(productsCount, productsCount + 6);

        nextProducts.forEach(product => {
            const productHTML = `
        <div class="px-12 py-8 bg-slate-50 w-full max-w-[450px] flex flex-col justify-center items-center shadow product-card" id="${product.id}">
            <img class="max-h-[160px] rounded mb-8" src="/uploads/${product.thumbnail}" alt="${product.name}" />
            <div class="w-full">
                <h3 class="text-xl text-center font-semibold marcellus w-content">${product.name}</h3>
                <p class="text-center marcellus text-slate-600 mb-6">${product.shortDescription}</p>
                <h2 class="text-center text-4xl my-5 marcellus">${product.price}</h2>
            </div>
        </div>
      `;

            this.productsContainerTarget.insertAdjacentHTML('beforeend', productHTML);
        });

        this.toggleLoadMoreButton();
    }
}

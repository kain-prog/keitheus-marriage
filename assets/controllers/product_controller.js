import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["frame", "productsContainer", "loadMoreButton", "state"];
    static values = {
        products: Array,
        totalProducts: Number,
        pageSize: { type: Number, default: 6 },
    };

    connect() {
        this.hydrateFromFrame();
        this.updateButton();
    }

    onFrameLoad() {
        this.hydrateFromFrame();
        this.updateButton();
    }

    loadMore() {
        const count = this.countRendered();
        const list = this.productsValue || [];
        const next = list.slice(count, count + this.pageSizeValue);

        if (!next.length) {
            this.updateButton();
            return;
        }

        next.forEach((product) => {
            const html = this.renderProduct(product);
            this.productsContainerTarget.insertAdjacentHTML("beforeend", html);
        });

        this.updateButton();
    }


    hydrateFromFrame() {
        const sourceEl = this.hasStateTarget ? this.stateTarget : this.frameTarget;

        const productsRaw =
            sourceEl.getAttribute("data-product-products-value") ??
            sourceEl.dataset.productProductsValue ??
            "[]";

        const totalRaw =
            sourceEl.getAttribute("data-product-total-products-value") ??
            sourceEl.dataset.productTotalProductsValue ??
            "0";

        try {
            this.productsValue = JSON.parse(productsRaw);
        } catch {
            this.productsValue = [];
        }
        this.totalProductsValue = Number(totalRaw) || 0;
    }

    updateButton() {
        if (!this.hasLoadMoreButtonTarget) return;

        const total = this.totalProductsValue || 0;
        const count = this.countRendered();

        this.loadMoreButtonTarget.style.display =
            total > 0 && count < total ? "block" : "none";
    }

    countRendered() {
        return this.productsContainerTarget.querySelectorAll(".product-card").length;
    }

    renderProduct(product) {
        const name = this.escape(String(product?.name ?? ""));
        const shortDescription = this.escape(String(product?.shortDescription ?? ""));
        const thumb = this.escape(String(product?.thumbnail ?? ""));
        const id = this.escape(String(product?.id ?? ""));
        const paymentUrl = this.escape(String(product?.paymentUrl ?? "#"));
        const isPresented = !!product?.is_presented;

        console.log(isPresented, 'aaaaaaaaaaaa');

        const categoriesHTML =
            Array.isArray(product?.categories) && product.categories.length
                ? `
      <div class="flex items-center justify-center gap-3">
        ${product.categories
                    .map(
                        (c) => `
          <span class="font-semibold text-[10px] md:text-xs marcellus px-4 py-1 rounded-full border select-none border-slate-300 bg-slate-300">
            ${this.escape(String(c?.name ?? ""))}
          </span>`
                    )
                    .join("")}
      </div>`
                : "";

        const priceHTML = `
      <h2 class="text-center text-xl md:text-4xl my-5 marcellus">
        ${this.formatBRL(product?.price)}
      </h2>`;

        const actionHTML = isPresented
            ? `
        <a class="select-none opacity-60 m-auto max-w-[190px] md:w-full md:max-w-full cursor-default block marcellus py-2 rounded text-slate-900 bg-slate-200 border border-slate-300 hover:bg-slate-300 text-center transition duration-150 ">
          <i class="fas fa-check text-sm"></i>
          <span class="text-xs md:text-[16px]">Já Presenteado</span>
        </a>`
            : `
        <a href="${paymentUrl}" class="select-none m-auto max-w-[190px] md:w-full md:max-w-full transition duration-150 w-full block py-2 rounded text-slate-50 text-center cursor-pointer bg-slate-700 hover:bg-slate-600 marcellus">
          <i class="fa-solid fa-gift"></i>
          <span class="text-xs md:text-[16px] font-semibold marcellus">Presentear</span>
        </a>`;

        return `
      <div class="px-12 py-8 bg-slate-50 w-full max-w-[380px] flex flex-col justify-center items-center shadow product-card" id="${id}">
        <img class="max-h-[95px] md:max-h-[160px] rounded mb-4 md:mb-8" src="/uploads/${thumb}" alt="${name}" />
        <div class="w-full">
          <h3 class="text-md md:text-xl text-center font-semibold marcellus w-content">${name}</h3>
          <p class="text-xs md:text-sm text-center marcellus text-slate-600 mb-3 md:mb-6">${shortDescription}</p>
          ${categoriesHTML}
          ${priceHTML}
          ${actionHTML}
        </div>
      </div>`;
    }

    formatBRL(value) {
        const num =
            typeof value === "number" ? value : Number(String(value).replace(/[^\d.-]/g, ""));
        return Number.isFinite(num)
            ? new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(num)
            : String(value ?? "");
    }

    escape(str) {
        return str
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }
}

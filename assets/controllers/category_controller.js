import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["item"]

    connect() {
        console.log("✅ Category controller conectado")
    }

    toggle(event) {
        event.preventDefault()
        const clicked = event.currentTarget
        clicked.classList.toggle("bg-slate-300")

        const selected = this.itemTargets
            .filter(el => el.classList.contains("bg-slate-300"))
            .map(el => el.dataset.categoryName)

        // Monta a URL final
        const url = selected.length > 0
            ? `/product/category/${encodeURIComponent(selected.join(','))}`
            : `/presentes`

        // Atualiza o turbo-frame manualmente
        const frame = document.querySelector("turbo-frame#products")
        if (frame) {
            frame.src = url
        }
    }
}

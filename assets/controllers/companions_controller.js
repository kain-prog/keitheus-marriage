import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["toggle", "panel", "items", "template", "count", "max", "hidden", "name", "child"]
    static values = {
        companionsInputSelector: String
    }

    connect() {
        this.updateMax()
        this.observeCompanionsInput()
        this.serialize()
    }

    disconnect() {
        if (this._unsub) this._unsub()
    }

    // abre/fecha o painel
    toggle() {
        this.panelTarget.classList.toggle("hidden", !this.toggleTarget.checked)
    }

    // adiciona um item (respeitando limite)
    add() {
        const max = this.currentMax()
        const current = this.itemsTarget.children.length

        if (current >= max) return this.bump()

        const node = this.templateTarget.content.firstElementChild.cloneNode(true)
        this.itemsTarget.appendChild(node)
        this.updateCount()
        this.serialize()
    }

    // remove um item
    remove(event) {
        event.currentTarget.closest("div").remove()
        this.updateCount()
        this.serialize()
    }

    // serializa em JSON -> hidden
    serialize() {
        const entries = Array.from(this.itemsTarget.querySelectorAll("[data-companions-target='name']")).map((input, idx) => {
            const wrapper = input.closest("div")
            const child = wrapper.querySelector("[data-companions-target='child']")
            return {
                name: (input.value || "").trim(),
                child: !!(child && child.checked)
            }
        }).filter(e => e.name.length > 0)

        this.hiddenTarget.value = JSON.stringify(entries)
    }

    // helpers
    updateCount() {
        this.countTarget.textContent = String(this.itemsTarget.children.length)
        this.serialize()
    }

    updateMax() {
        this.maxTarget.textContent = String(this.currentMax())
    }

    currentMax() {
        const el = document.querySelector(this.companionsInputSelectorValue)
        const n = parseInt(el ? el.value : "0", 10)
        return Number.isFinite(n) && n >= 0 ? n : 0
    }

    observeCompanionsInput() {
        const el = document.querySelector(this.companionsInputSelectorValue)
        if (!el) return
        const handler = () => {
            const max = this.currentMax()
            this.updateMax()
            // se excedeu, corta
            while (this.itemsTarget.children.length > max) {
                this.itemsTarget.lastElementChild.remove()
            }
            this.updateCount()
        }
        el.addEventListener("input", handler)
        this._unsub = () => el.removeEventListener("input", handler)
    }

    // feedback rápido quando atinge limite
    bump() {
        this.maxTarget.classList.add("animate-pulse")
        setTimeout(() => this.maxTarget.classList.remove("animate-pulse"), 500)
    }

    // atualizar JSON ao digitar/marcar
    // delegação de eventos
    // (no template, inputs não existiam no connect)
    // -> usar capture no container
    panelTargetConnected() {
        this.element.addEventListener("input", (e) => {
            if (e.target.matches("[data-companions-target='name'], [data-companions-target='child']")) {
                this.serialize()
            }
        }, true)
    }
}

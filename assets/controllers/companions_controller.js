import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["toggle", "panel", "items", "template", "count", "max", "hidden", "name", "child"]
    static values = {
        companionsInputSelector: String
    }

    connect() {
        this.updateMax()
        this.observeCompanionsInput()
        this.syncFromCompanionsNumber()
        this.serialize()
    }

    disconnect() {
        if (this._unsub) this._unsub()
    }

    toggle() {
        this.panelTarget.classList.toggle("hidden", !this.toggleTarget.checked)
    }

    add() {
        const max = this.currentMax()
        const current = this.itemsTarget.children.length

        if (current >= max) return this.bump()

        const node = this.templateTarget.content.firstElementChild.cloneNode(true)
        this.itemsTarget.appendChild(node)
        this.updateCount()
        this.serialize()
    }

    remove(event) {
        event.currentTarget.closest("div").remove()
        this.updateCount()
        this.serialize()
    }

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
            this.updateMax()
            this.syncFromCompanionsNumber()
        }
        el.addEventListener("input", handler)
        this._unsub = () => el.removeEventListener("input", handler)
    }

    syncFromCompanionsNumber() {
        const max = this.currentMax()

        if (this.hasToggleTarget) this.toggleTarget.checked = max > 0
        if (this.hasPanelTarget) this.panelTarget.classList.toggle("hidden", !(max > 0))

        while (this.itemsTarget.children.length > max) {
            this.itemsTarget.lastElementChild.remove()
        }

        this.updateCount()

        if (max === 0) {
            this.itemsTarget.innerHTML = ""
            this.updateCount()
            this.serialize()
        }
    }

    bump() {
        this.maxTarget.classList.add("animate-pulse")
        setTimeout(() => this.maxTarget.classList.remove("animate-pulse"), 500)
    }

    panelTargetConnected() {
        this.element.addEventListener("input", (e) => {
            if (e.target.matches("[data-companions-target='name'], [data-companions-target='child']")) {
                this.serialize()
            }
        }, true)
    }
}

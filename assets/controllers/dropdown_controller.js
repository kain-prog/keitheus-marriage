import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["menu", "input"]

    connect() {
        this.isOpen = false
        this.highlightedIndex = -1
        this.boundClickOutside = this.clickOutside.bind(this)
        document.addEventListener("click", this.boundClickOutside)
    }

    disconnect() {
        document.removeEventListener("click", this.boundClickOutside)
    }

    select(event) {
        event.preventDefault();

        const item = event.currentTarget;
        const value = item.dataset.value;
        const text = item.textContent.trim();

        const label = this.element.querySelector('#dropdown-label');
        if (label) {
            label.textContent = text;
        }

        const hiddenSelect = document.getElementById('guest_confirmation_guest');
        if (hiddenSelect) {
            hiddenSelect.value = value;
        }

        this.isOpen = false;
        this.menuTarget.classList.add("hidden");


        const inputGuestNotCome = document.querySelector('#div-not-come');

        if(value || value !== ""){
            inputGuestNotCome.classList.remove('hidden');
        }else{
            inputGuestNotCome.classList.add('hidden');
        }
    }

    toggle(event) {
        event.stopPropagation()
        this.isOpen = !this.isOpen
        this.menuTarget.classList.toggle("hidden", !this.isOpen)

        if (this.isOpen) {
            this.inputTarget.focus()
            this.highlightedIndex = -1
        }
    }

    filter() {
        const searchTerm = this.inputTarget.value.toLowerCase()
        const items = this.menuTarget.querySelectorAll('a')

        items.forEach((item) => {
            const text = item.textContent.toLowerCase()
            item.classList.toggle("hidden", !text.includes(searchTerm))
        })
        this.highlightedIndex = -1
        this.removeHighlights()
    }

    keydown(event) {
        const items = Array.from(this.menuTarget.querySelectorAll('a:not(.hidden)'))
        if (!items.length) return

        switch (event.key) {
            case "ArrowDown":
                event.preventDefault()
                this.highlightedIndex = (this.highlightedIndex + 1) % items.length
                this.updateHighlight(items)
                break

            case "ArrowUp":
                event.preventDefault()
                this.highlightedIndex = (this.highlightedIndex - 1 + items.length) % items.length
                this.updateHighlight(items)
                break

            case "Enter":
                event.preventDefault()
                if (this.highlightedIndex >= 0) {
                    items[this.highlightedIndex].click()
                }
                break
        }
    }

    updateHighlight(items) {
        this.removeHighlights()
        if (this.highlightedIndex >= 0) {
            items[this.highlightedIndex].classList.add("bg-blue-100")
            items[this.highlightedIndex].scrollIntoView({ block: "nearest" })
        }
    }

    removeHighlights() {
        this.menuTarget.querySelectorAll('a').forEach((item) => {
            item.classList.remove("bg-blue-100")
        })
    }

    clickOutside(event) {
        if (!this.element.contains(event.target)) {
            this.isOpen = false
            this.menuTarget.classList.add("hidden")
        }
    }
}

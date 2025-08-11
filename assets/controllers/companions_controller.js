import {Controller} from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["toggle", "panel", "items", "template", "count", "max", "hidden", "name", "child"]
    static values = {
        companionsInputSelector: String
    }

    connect() {
        this.updateMax();
        this.observeConfirmationRadios();
        this.observeCompanionsInput();
        this.syncFromCompanionsNumber();
        this.lockConfirmationByCompanions?.();
        this.lockByConfirmation();
        this.serialize();
    }

    disconnect() {
        if (this._unsub) this._unsub();
        if (this._unsubConfirm) this._unsubConfirm();
    }

    toggle(event) {
        const max = this.currentMax();

        if (max > 0 && !this.toggleTarget.checked) {
            console.log('aaa');
            event.preventDefault()
            this.toggleTarget.checked = true;
            this.panelTarget.classList.remove("hidden");
            this.bump();
            return;
        }

        this.panelTarget.classList.toggle("hidden", !this.toggleTarget.checked);
    }

    add() {
        const max = this.currentMax();
        const current = this.itemsTarget.children.length;
        if (current >= max) return this.bump();

        const hadAdult = this.adultCount() > 0;

        const node = this.templateTarget.content.firstElementChild.cloneNode(true);
        this.itemsTarget.appendChild(node);

        const cb = this.itemsTarget.lastElementChild.querySelector("[data-companions-target='child']");
        if (cb) {
            cb.removeAttribute('checked');
            cb.checked = hadAdult;
        }

        this.updateCount();
        this.serialize();
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

    adultCount() {
        const rows = Array.from(this.itemsTarget.children);
        return rows.reduce((acc, row) => {
            const child = row.querySelector("[data-companions-target='child']");
            const isAdult = child ? !child.checked : true;
            return acc + (isAdult ? 1 : 0);
        }, 0);
    }

    observeCompanionsInput() {
        const el = document.querySelector(this.companionsInputSelectorValue)
        if (!el) return
        const handler = () => {
            this.updateMax();
            this.syncFromCompanionsNumber();
            this.lockConfirmationByCompanions();
        };

        el.addEventListener("input", handler)
        this._unsub = () => el.removeEventListener("input", handler)
    }

    observeConfirmationRadios() {
        const radios = document.querySelectorAll("[name='guest_confirmation[is_confirmed]']");
        if (!radios.length) return;
        const handler = () => {
            this.lockByConfirmation();
            this.syncFromCompanionsNumber();
        };
        radios.forEach(r => r.addEventListener("change", handler));
        this._unsubConfirm = () => radios.forEach(r => r.removeEventListener("change", handler));
    }

    lockConfirmationByCompanions() {
        const yes = document.querySelector("[name='guest_confirmation[is_confirmed]'][value='1']");
        const no  = document.querySelector("[name='guest_confirmation[is_confirmed]'][value='0']");
        const hasCompanions = this.currentMax() > 0;

        if (!yes || !no) return;

        if (hasCompanions) {
            yes.checked = true;
            no.checked = false;
            no.disabled = true;

            no.closest('div')?.classList.add('opacity-50','pointer-events-none');
        } else {
            no.disabled = false;
            no.closest('div')?.classList.remove('opacity-50','pointer-events-none');
        }
    }

    lockByConfirmation() {
        const yes = document.querySelector("[name='guest_confirmation[is_confirmed]'][value='1']");
        const no  = document.querySelector("[name='guest_confirmation[is_confirmed]'][value='0']");
        const companionsInput = document.querySelector(this.companionsInputSelectorValue);
        const isNo = !!no && no.checked === true;

        if (isNo) {
            // zera e desabilita número
            if (companionsInput) {
                companionsInput.value = "0";
                companionsInput.setAttribute("disabled", "disabled");
            }

            // desmarca/desabilita toggle e esconde painel
            if (this.hasToggleTarget) {
                this.toggleTarget.checked = false;
                this.toggleTarget.setAttribute("disabled", "disabled");
            }
            if (this.hasPanelTarget) this.panelTarget.classList.add("hidden");

            // limpa itens e JSON
            if (this.itemsTarget) this.itemsTarget.innerHTML = "";
            this.updateCount();
            this.serialize();
        } else {
            // reabilita quando "Sim"
            if (companionsInput) companionsInput.removeAttribute("disabled");
            if (this.hasToggleTarget) this.toggleTarget.removeAttribute("disabled");
        }
    }

    syncFromCompanionsNumber() {
        const max = this.currentMax();

        if (this.hasToggleTarget) {
            this.toggleTarget.checked = max > 0;
            this.toggleTarget.title = max > 0
                ? "Para desmarcar, defina o número de acompanhantes como 0."
                : "";
        }
        if (this.hasPanelTarget) {
            this.panelTarget.classList.toggle("hidden", !(max > 0));
        }

        while (this.itemsTarget.children.length > max) {
            this.itemsTarget.lastElementChild.remove();
        }
        if (max === 0) this.itemsTarget.innerHTML = "";

        this.updateCount();
        this.serialize();
        this.lockByConfirmation();
        this.lockConfirmationByCompanions();
    }

    bump() {
        this.maxTarget.classList.add("animate-pulse")
        setTimeout(() => this.maxTarget.classList.remove("animate-pulse"), 500)
    }

    panelTargetConnected() {
        this.element.addEventListener("input", (e) => {
            if (e.target.matches("[data-companions-target='name'], [data-companions-target='child']")) {

                if (e.target.matches("[data-companions-target='child']")) {
                    // Se o usuário está desmarcando => tornando ADULTO
                    if (e.target.checked === false) {
                        // Após a mudança, contamos os adultos; se passar de 1, revertimos
                        if (this.adultCount() > 1) {
                            e.target.checked = true; // volta a criança
                            this.bump();
                        }
                    }
                    // Se marcar como criança, sempre permitido (pode ficar 0 adultos)
                }

                this.serialize();
            }
        }, true);
    }
}

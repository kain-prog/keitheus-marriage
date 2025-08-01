import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["responseS", "responseD", "toastSuccess", "toastDanger"];

    connect() {
        console.log("✅ Controller presence conectado");
    }

    async submit(event) {
        event.preventDefault();

        const form = event.target;
        const guestSelect = form.querySelector("[name='guest_confirmation[guest]']");

        if (!guestSelect.value || guestSelect.value === "") {
            this.inputVoidStyle();
            this.showError("Por favor, selecione o seu nome antes de confirmar.");
            return;
        }

        try {
            const response = await fetch('/confirmar-presenca', {
                method: form.method,
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(this.toastSuccessTarget);
            } else {
                this.showToast(this.toastDangerTarget);
            }

            if (this.hasResponseSTarget) {
                this.responseSTarget.textContent = data.message || "Formulário enviado com sucesso.";
            }

        } catch (error) {
            this.showToast(this.toastDangerTarget);

            if (this.hasResponseDTarget) {
                this.responseDTarget.textContent = data.message || "Erro ao confirmar presença.";
            }
        }
    }

    showToast(toastElement) {
        if (this.hasToastSuccessTarget) this.toastSuccessTarget.classList.add("hidden");
        if (this.hasToastDangerTarget) this.toastDangerTarget.classList.add("hidden");

        toastElement.classList.remove("hidden");

        setTimeout(() => {
            toastElement.classList.add("hidden");
        }, 3000);
    }

    showError(message) {
        if (this.responseDTarget) {
            this.responseDTarget.textContent = message;
        }
        this.showToast(this.toastDangerTarget);
    }

    inputVoidStyle(){
        const inputSelect = document.querySelector('#select-guest');
        const dropdownSearch = document.querySelector('#dropdown-search');

        dropdownSearch.classList.remove('ring-slate-300');

        inputSelect.classList.add('border-red-600');
        dropdownSearch.classList.add('ring-red-600');

        setTimeout(() => {
            inputSelect.classList.remove('border-red-600');
            dropdownSearch.classList.remove('ring-red-600');

            dropdownSearch.classList.add('ring-slate-300');
        }, 3000);
    }
}

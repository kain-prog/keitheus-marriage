import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["SendForm", "responseS", "responseD", "toastSuccess", "toastDanger"];

    connect() {
        console.log("✅ Controller Presence conectado");
    }

    async submit(event) {
        event.preventDefault();

        const form = event.target;
        const guestSelect = form.querySelector("[name='guest_confirmation[guest]']");
        const submitButton = form.querySelector("#confirm-btn");

        const disableBtn = () => {
            submitButton.classList.remove("text-zinc-700 bg-zinc-300", "cursor-pointer", "hover:text-gray-500", "hover:bg-zinc-200");
            submitButton.classList.add("cursor-normal", "bg-zinc-100", "text-200");
            submitButton.setAttribute("disabled", "true");
        };
        const enableBtn = () => {
            submitButton.classList.add("text-zinc-700 bg-zinc-300", "cursor-pointer", "hover:text-gray-500", "hover:bg-zinc-200");
            submitButton.classList.remove("text-zinc-300 cursor-normal", "bg-zinc-100", "text-200");
            submitButton.removeAttribute("disabled"); // << importante
        };

        disableBtn();

        if (!guestSelect.value || guestSelect.value === "") {
            this.inputVoidStyle();
            this.showError("Por favor, selecione o seu nome antes de confirmar.");
            enableBtn();
            return;
        }

        const confirmedYes = form.querySelector("[name='guest_confirmation[is_confirmed]'][value='1']")?.checked === true;
        const confirmedNo  = form.querySelector("[name='guest_confirmation[is_confirmed]'][value='0']")?.checked === true;

        const companionsNumberEl = form.querySelector("#guest_confirmation_companions_number");
        const max = parseInt(companionsNumberEl?.value || "0", 10);
        const companionsJson = form.querySelector("#guest_confirmation_companions_list")?.value || "[]";

        let companions = [];
        try { companions = JSON.parse(companionsJson) || []; } catch { companions = []; }

        const adultCount = companions.reduce((acc, c) => acc + (c && c.child === false ? 1 : 0), 0);

        if (Number.isNaN(max) || max < 0) {
            this.showError("Número de acompanhantes inválido.");
            enableBtn();
            return;
        }

        if (confirmedNo) {
            if (max > 0 || companions.length > 0) {
                this.showError("Com 'Não', não é permitido informar acompanhantes.");
                enableBtn();
                return;
            }
        }

        if (max > 0 && !confirmedYes) {
            this.showError("Com acompanhantes, a confirmação precisa ser 'Sim'.");
            enableBtn();
            return;
        }

        if (companions.length > max) {
            this.showError(`Você adicionou mais acompanhantes do que o permitido (${companions.length}/${max}). Remova alguns ou ajuste o número.`);
            enableBtn();
            return;
        }

        if (max > 0 && companions.length === 0) {
            this.showError("Informe ao menos 1 acompanhante ou reduza o número para 0.");
            enableBtn();
            return;
        }

        if (adultCount > 1) {
            this.showError("Só é permitido 1 acompanhante adulto; os demais precisam ser crianças.");
            enableBtn();
            return;
        }

        try {
            const response = await fetch('/confirmar-presenca', {
                method: form.method,
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(this.toastSuccessTarget);
                this.clearForm();
                enableBtn();
            } else {
                this.showError(data.errors);
                enableBtn();
                enableBtn();

            }

            if (this.hasResponseSTarget) {
                this.responseSTarget.textContent = data.message || "Formulário enviado com sucesso.";
            }

        } catch (error) {
            this.showToast(this.toastDangerTarget);
            enableBtn();
            if (this.hasResponseDTarget) {
                this.responseDTarget.textContent = "Erro ao confirmar presença.";
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
        console.log('aaaaaaaaaaaa');
        if (this.responseDTarget) {
            console.log('inside if aaaaaaaaaaaa');
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

    clearForm() {
        if (this.hasSendFormTarget) {
            this.sendFormTarget.reset();
        }

        const dropdownLabel = document.querySelector("#dropdown-label");
        const guestInput = document.querySelector("[name='guest_confirmation[guest]']");
        const dropdownMenu = document.querySelector("#dropdown-search");

        if (dropdownLabel) dropdownLabel.textContent = "Selecione o seu nome...";
        if (guestInput) guestInput.value = "";
        if (dropdownMenu) dropdownMenu.classList.add("hidden");

        const messageInput = document.querySelector("[name='guest_confirmation[message]']");
        if (messageInput) messageInput.value = "";

        const companionsNumber = document.querySelector("[name='guest_confirmation[companions_number]']");
        if (companionsNumber) companionsNumber.value = "";

        const radios = document.querySelectorAll("[name='guest_confirmation[is_confirmed]']");
        radios.forEach(radio => radio.checked = false);
    }
}

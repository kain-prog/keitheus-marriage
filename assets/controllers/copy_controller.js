import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["pixCode", "message"];

    copy(event) {
        event.preventDefault();

        const text = this.pixCodeTarget.value;

        navigator.clipboard.writeText(text).then(() => {
            this.messageTarget.classList.remove("hidden");
            setTimeout(() => {
                this.messageTarget.classList.add("hidden");
            }, 2000);
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const moneyInput = document.querySelector('#Product_price');

    if (moneyInput) {
        moneyInput.addEventListener('input', function(e) {
            let value = e.target.value;
            value = value.replace(/\D/g, '');
            value = (value / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            e.target.value = value;
        });

        moneyInput.addEventListener('blur', function(e) {
            let value = e.target.value;

            value = value.replace(/\./g, '').replace(',', ',');

            e.target.value = value;
        });
    }
});

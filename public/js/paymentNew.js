// Permet l'écoute du chargement du DOM
window.addEventListener('DOMContentLoaded', (event) => {
    const stripe = Stripe(stripePublicKey)
    const elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create("payment");

    paymentElement.mount("#payment-element");
    document.querySelector('#payment-form').addEventListener('submit', async e => {

        e.preventDefault()

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: redirectAfterSuccessUrl
            }
        });

        if (error.type === "card_error" || error.type === "validation_error") {
            showMessage(error.message)
        } else {
            showMessage("An unexpected error occured.");
        }
    })
});

function showMessage(messageText) {

    const messageContainer = document.querySelector("#payment-message");
    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;

    setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageText.textContent = "";
    }, 4000);
}
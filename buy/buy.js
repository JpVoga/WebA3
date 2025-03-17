const paymentMethodSelect = document.getElementById("paymentMethodSelect");
const paymentKeyInputLabel = document.getElementById("paymentKeyInputLabel");
const paymentKeyInput = document.getElementById("paymentKeyInput");
const paymentKeyErrorDisplay = document.getElementById("paymentKeyErrorDisplay");

function isCardSelected()
{
    return (paymentMethodSelect.value.toString().toLowerCase().includes("card"));
}

function isPixSelected()
{
    return (paymentMethodSelect.value.toString().toLowerCase().includes("pix"));
}

function validateCardNumber(cardNumber)
{
    return (
        (cardNumber.length >= MIN_CARD_NUMBER_DIGITS) &&
        (cardNumber.length <= MAX_CARD_NUMBER_DIGITS) &&
        (!(isNaN(parseInt(cardNumber))))
    );
}

function updatePaymentKeyInputLabel()
{
    if (isCardSelected()) paymentKeyInputLabel.innerHTML = "Número do cartão: ";
    else if (isPixSelected()) paymentKeyInputLabel.innerHTML = "Chave Pix: ";
    else paymentKeyInputLabel = "Chave de pagamento: ";
}

function updatePaymentKeyErrorDisplay()
{
    if ((isCardSelected()) && (!(validateCardNumber(paymentKeyInput.value))))
    {
        paymentKeyErrorDisplay.innerHTML = "Número de cartão inválido.";
    }
    else paymentKeyErrorDisplay.innerHTML = "";
}


if ((paymentMethodSelect != null) && (paymentKeyInputLabel != null) && (paymentKeyInput != null))
{
    paymentMethodSelect.onchange = (() => {updatePaymentKeyInputLabel(); updatePaymentKeyErrorDisplay();});

    if (paymentKeyErrorDisplay != null)
    {
        paymentKeyInput.onblur = updatePaymentKeyErrorDisplay;
    }

    updatePaymentKeyInputLabel();
}
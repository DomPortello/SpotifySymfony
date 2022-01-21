if(document.querySelector('[data-stripe-payment]') !== null){
    //@ts-ignore
    const stripe = Stripe("pk_test_51KKJ6ZJ8gPt2IM0cJJRqV3NtezRKAFpovQqi6xTMji6Ic3f6QpWLjyRHST3GbuyP9aLhWLeJ13pxhm39Ezh1juHF00kSCiB0sn");
    const elements = stripe.elements();
    let secretKey = document.querySelector('[data-stripe-payment]').getAttribute('data-stripe-payment');
    let redirectProcessUrl = document.querySelector('[data-redirect-url]').getAttribute('data-redirect-url');
    let user = document.querySelector('[data-user-mail]').getAttribute('data-user-mail');

    let card = elements.create('card');
    card.mount('#card-element');

    card.on("change", ({error}) => {
        let displayError = document.querySelector('#card-errors');
        if(error){
            displayError.textContent = error.message;
        }else{
            displayError.textContent = '';
        }
    })

    let form = document.querySelector('#payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        stripe.confirmCardPayment(secretKey,{
            payment_method: {
                card: card,
                billing_details: {
                    name:user
                }
            }
        }).then(function(result){
            redirectPost(redirectProcessUrl, result.paymentIntent);
        })
    })
}

function redirectPost(url, data){
    let form = document.createElement('form');
    document.body.appendChild(form);
    form.method = 'post';
    form.action = url;
    for(let name in data){
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }
    form.submit();
}
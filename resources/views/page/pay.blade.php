<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Payment</title>
    <style>
        body {
            font-family: Arial;
            padding: 50px;
            text-align: center;
        }

        .success {
            color: green;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        #payBtn,
        #goSuccessBtn {
            padding: 10px 20px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        #payBtn:hover,
        #goSuccessBtn:hover {
            background-color: #e55b5b;
        }
    </style>
</head>

<body>
    <h2>Mock Payment</h2>
    <p id="payment-method-display"></p>
    <p id="payment-ref-display"></p>

    <div id="payment-container">
        <button id="payBtn">Pay Now</button>
    </div>

    <script>
        const paymentMethod = sessionStorage.getItem('temp_method') || 'Unknown Payment';
        
        if (paymentMethod === 'Cash on Delivery (COD)') {
            sessionStorage.setItem('payment_done', 'true');
            sessionStorage.setItem('temp_ref', 'COD-PAYMENT');
            window.location.href = "{{ route('cart.checkout') }}";
        }

        const paymentDone = sessionStorage.getItem('payment_done');
        const paymentRef = sessionStorage.getItem('temp_ref');

        document.getElementById('payment-method-display').innerText =
            `Payment Method: ${paymentMethod}`;

        const container = document.getElementById('payment-container');

        if (paymentDone === 'true' && paymentRef) {
            showSuccess(paymentRef);
        } else {
            document.getElementById('payBtn').addEventListener('click', function() {
                const reference = 'MOCK-' + Math.random().toString(36).substring(2, 10).toUpperCase();

                sessionStorage.setItem('payment_done', 'true');
                sessionStorage.setItem('temp_ref', reference);

                showSuccess(reference);
            });
        }

        function showSuccess(reference) {
            container.innerHTML = `
                <p class="success">Payment Successful!</p>
                <p>Reference: ${reference}</p>
                <button id="goSuccessBtn">Go to Success Page</button>
            `;

            document.getElementById('goSuccessBtn').addEventListener('click', () => {
                window.location.href = "{{ route('cart.checkout') }}";
            });
        }
    </script>
</body>

</html>
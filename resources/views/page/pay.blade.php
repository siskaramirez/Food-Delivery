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
        const paymentMethod = localStorage.getItem('user_payment') || 'Unknown';
        const paymentDone = localStorage.getItem('payment_done');
        const paymentRef = localStorage.getItem('payment_reference');

        document.getElementById('payment-method-display').innerText =
            `Payment Method: ${paymentMethod}`;

        const container = document.getElementById('payment-container');

        if (paymentDone === 'true' && paymentRef) {
            showSuccess(paymentRef);
        } else {
            document.getElementById('payBtn').addEventListener('click', function () {
                const reference = 'MOCK-' + Math.random().toString(36).substring(2, 10).toUpperCase();

                localStorage.setItem('payment_done', 'true');
                localStorage.setItem('payment_reference', reference);

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
                localStorage.removeItem('payment_done');
                localStorage.removeItem('payment_reference');
                window.location.href = '/cart/checkout';
            });
        }
    </script>
</body>
</html>
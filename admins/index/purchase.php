<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .selection {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .selection__title {
            font-size: 24px;
            margin: 20px;
        }

        .selection__items {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            justify-content: center;
        }

        .select-card {
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s, transform 0.2s;
            cursor: pointer;
            position: relative;
            border: 2px solid #fff;
            /* Add a border for the default state */
        }

        .select-card:hover {
            background-color: #fb9149;
            color: #fff;
            transform: scale(1.02);
        }

        .select-card__input {
            display: none;
        }

        /* Style select-card when the input is checked */
        .select-card__input:checked+.select-card__label {
            color: #14a39a;
        }

        .select-card__label {
            padding: 20px;
        }

        .select-card__label__title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .select-card__label p {
            font-size: 14px;
            margin-top: 10px;
        }

        .checkbox {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background-color: #fff;
            border: 2px solid #14a39a;
            border-radius: 50%;
            cursor: pointer;
        }

        .select-card__input:checked+.select-card__label .checkbox::before {
            content: "";
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 2px;
            height: 6px;
            border: solid coral;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 3px;
            border-radius: 2px;
            transition: transform 0.2s ease;
            transform-origin: center;
        }

        /* Rotate the tick icon when checked */
        .select-card__input:checked+.select-card__label .checkbox::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }
    </style>
</head>

<body>
    <form data-v-3a6f080c="" class="selection">
        <h1 data-v-3a6f080c="" class="selection__title">
            How would you like to make your transfer?
        </h1>
        <div data-v-3a6f080c="" class="selection__items selection__items--wrap cards"><label
                class="select-card selection__item" data-v-3a6f080c="" for="select-card__1fw0ahc0v"><input
                    name="transfer-select" type="radio" class="select-card__input" value="account"
                    id="select-card__1fw0ahc0v"> <label class="select-card__label" for="select-card__1fw0ahc0v">
                    <h3 class="select-card__label__title">Transfer to Bank account</h3>
                    <p>Send money directly from your Flutterwave account wallet to one or more bank accounts instantly.
                    </p>
                    <div class="checkbox"></div>
                </label></label> <label class="select-card selection__item" data-v-3a6f080c=""
                for="select-card__oy6wafzri"><input name="transfer-select" type="radio" class="select-card__input"
                    value="momo" id="select-card__oy6wafzri"> <label class="select-card__label"
                    for="select-card__oy6wafzri">
                    <h3 class="select-card__label__title">Transfer to Mobile money</h3>
                    <p>Send money to a mobile phone number seamlessly using Mobile Money Transfer. Bulk transfer options
                        also available.</p>
                    <div class="checkbox"></div>
                </label></label> <label class="select-card selection__item" data-v-3a6f080c=""
                for="select-card__x9cwa4e7j"><input name="transfer-select" type="radio" class="select-card__input"
                    value="wallet" id="select-card__x9cwa4e7j"> <label class="select-card__label"
                    for="select-card__x9cwa4e7j">
                    <h3 class="select-card__label__title">Transfer to Flutterwave account</h3>
                    <p>Send money in any currency from one Flutterwave account to another using a merchant ID.</p>
                    <div class="checkbox"></div>
                </label></label> <!----> <label class="select-card selection__item" data-v-3a6f080c=""
                for="select-card__48hcc8mnb"><input name="transfer-select" type="radio" class="select-card__input"
                    value="wallet/self" id="select-card__48hcc8mnb"> <label class="select-card__label"
                    for="select-card__48hcc8mnb">
                    <h3 class="select-card__label__title">Transfer Between Currency Balances</h3>
                    <p>This type of transfer allows you to send money between your flutterwave balances.</p>
                    <div class="checkbox"></div>
                </label></label></div>
                <br>
        <button href="purchase.php" class="selection__action form-control form-control-lg" type="submit"
            autofocus="autofocus" to="/dashboard/payments/transfers/new/account" data-v-3a6f080c="">
            Start transfer
        </button>
    </form>
</body>

</html>
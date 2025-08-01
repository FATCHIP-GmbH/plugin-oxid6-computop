<style>
    .credit-card-form {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f9f9f9;
    }
    .credit-card-form h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group input, .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .form-group .form-control {
        display: block;
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .form-group .form-control:focus {
        border-color: #007bff;
        outline: none;
    }
    .form-group .form-error {
        color: red;
        font-size: 0.875em;
    }
    .submit-btn {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        border: none;
        color: white;
        border-radius: 4px;
        cursor: pointer;
    }
    .submit-btn:hover {
        background-color: #0056b3;
    }
</style>

[{capture name="fatchip_computop_creditcard_shopurl"}]
    var ctShopUrl = '[{$oViewConf->ctGetShopUrl()}]';
[{/capture}]
[{oxscript add=$smarty.capture.fatchip_computop_creditcard_shopurl}]
[{oxscript include=$oViewConf->getModuleUrl('fatchip_computop_payments','assets/js/creditcard.js') priority=30 dynamic=$__oxid_include_dynamic}]

<div class="credit-card-form">
    <h2>Kreditkarte</h2>
    <form id="computopSilentPostForm" action="https://www.computop-paygate.com/paynow.aspx" method="post">
        <input type="hidden" name="sDeliveryAddressMD5" value="[{$oView->getDeliveryAddressMD5()}]">
        <input type="hidden" id="MerchantID" name="MerchantID" value="fatchip_oxid7">
        <input type="hidden" id="Len" name="Len" value="">
        <input type="hidden" id="Data" name="Data" value="">
        <input type="hidden" id="silentMode" name="SilentMode" value="1">

        <div class="form-group">
            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="number" class="form-control" placeholder="1234 5678 9012 3456" required>
            <div class="card-type-icon"></div>
            <div class="form-error" id="card-number-error"></div>
        </div>

        <div class="form-group">
            <label for="card-brand">Card Brand</label>
            <select id="card-brand" name="brand" class="form-control" required>
                <option value="">Select Card Brand</option>
                <option value="VISA">Visa</option>
                <option value="MasterCard">Mastercard</option>
            </select>
        </div>

        <div class="form-group">
            <label for="card-holder">Card Holder Name</label>
            <input type="text" id="card-holder" name="cardholder" class="form-control" placeholder="John Doe" required>
            <div class="form-error" id="card-holder-error"></div>
        </div>

        <div class="form-group">
            <label for="expiry-date">Expiry Date</label>
            <input type="text" id="expiry-date" name="expiryDate" class="form-control" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/\d{2}" required>
            <div class="form-error" id="expiry-date-error"></div>
        </div>

        <div class="form-group">
            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="securityCode" class="form-control" placeholder="123" required>
            <div class="form-error" id="cvv-error"></div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-highlight btn-lg w-100">
            Zahlungspflichtig bestellen
        </button>
    </form>
</div>
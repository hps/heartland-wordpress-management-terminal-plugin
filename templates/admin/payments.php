<?php require 'header.php'; ?>

<?php do_action('admin_notices'); ?>

<form id="iframes" method="post" action="<?php echo $page ?>">

    <input type="hidden" name="page" value="heartland-payments">
    <input type="hidden" name="action" value="charge">
    <input type="hidden" name="command" value="make-credit-payment">

    <div id="customer-panels" class="payment-wrappers">

        <div class="payment-panel">

            <h1>1. Customer Info</h1>

            <div class="field">
                <label for="FirstName" class="col-sm-2 control-label">First Name:</label>
                <input type="text" id="FirstName" name="FirstName" />
            </div>

            <div class="field">
                <label for="LastName" class="col-sm-2 control-label">Last Name:</label>
                <input type="text" id="LastName" name="LastName" />
            </div>

            <div class="field">
                <label for="PhoneNumber" class="col-sm-2 control-label">Phone Number:</label>
                <input type="text" id="PhoneNumber" name="PhoneNumber" />
            </div>

            <div class="field">
                <label for="Email" class="col-sm-2 control-label">Email:</label>
                <input type="text" id="Email" name="Email" />
            </div>

            <div class="field">
                <label for="Address" class="col-sm-2 control-label">Address:</label>
                <input type="text" id="Address" name="Address" />
            </div>

            <div class="field">
                <label for="City" class="col-sm-2 control-label">City:</label>
                <input type="text" id="City" name="City" />
            </div>

            <div class="field">
                <label for="State" class="col-sm-2 control-label">State:</label>
                <select Name="State" id="State">
                    <option value="">Pick a State</option>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                </select>
            </div>

            <div class="field">
                <label for="Zip" class="col-sm-2 control-label">Zip:</label>
                <input type="text" id="Zip" name="Zip" />
            </div>

        </div>

    </div>

    <div id="payment-panels" class="payment-wrappers">

        <div class="payment-panel">

            <h1>2. Card Info</h1>

            <label for="iframesCardNumber">Card Number:</label>
            <div class="iframeholder" id="iframesCardNumber"></div>

            <label for="iframesCardExpiration">Card Expiration:</label>
            <div class="iframeholder" id="iframesCardExpiration"></div>

            <label for="iframesCardCvv">Card CVV:</label>
            <div class="iframeholder" id="iframesCardCvv"></div>

        </div>

    </div>

    <div id="amount-panels" class="payment-wrappers">

        <div class="payment-panel">

            <h1>3. Payment Amount</h1>

            <div class="field">
                <div class="input-icon">
                    <i>$</i>
                    <input type="text" name="payment-amount" id="payment-amount" class="form-control" placeholder="0.00">
                </div>
            </div>

            <div id="iframesPaymentButton"></div>

            <input type="hidden" name="token_value">

        </div>

    </div>




</form>

<script type="text/javascript" src="https://api2.heartlandportico.com/SecureSubmit.v1/token/2.1/securesubmit.js"></script>
<script type="text/javascript">
  (function (document, Heartland) {
    var hps = new Heartland.HPS({
      publicKey: '<?php echo $this->getSetting('public_api_key'); ?>',
      type:      'iframe',
      fields: {
        cardNumber: {
          target:      'iframesCardNumber',
          placeholder: '•••• •••• •••• ••••'
        },
        cardExpiration: {
          target:      'iframesCardExpiration',
          placeholder: 'MM / YYYY'
        },
        cardCvv: {
          target:      'iframesCardCvv',
          placeholder: 'CVV'
        },
        submit: {
            target: 'iframesPaymentButton'
        }
      },
      style: {
        'input[type=text],input[type=tel]': {
            'box-sizing':'border-box',
            'display': 'block',
            'width': '100%',
            'height': '34px',
            'padding': '6px 12px',
            'font-size': '14px',
            'line-height': '1.42857143',
            'color': '#555',
            'background-color': '#fff',
            'background-image': 'none',
            'border': '1px solid #ccc',
            'border-radius': '4px',
            '-webkit-box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075)',
            'box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075)',
            '-webkit-transition': 'border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s',
            '-o-transition': 'border-color ease-in-out .15s,box-shadow ease-in-out .15s',
            'transition': 'border-color ease-in-out .15s,box-shadow ease-in-out .15s'
        },
        'input[type=text]:focus,input[type=tel]:focus': {
            'border-color': '#66afe9',
            'outline': '0',
            '-webkit-box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)',
            'box-shadow': 'inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)'
        },
        'input[type=submit]' : {
            'box-sizing':'border-box',
            'display': 'inline-block',
            'padding': '6px 12px',
            'margin-bottom': '0',
            'font-size': '14px',
            'font-weight': '400',
            'line-height': '1.42857143',
            'text-align': 'center',
            'white-space': 'nowrap',
            'vertical-align': 'middle',
            '-ms-touch-action': 'manipulation',
            'touch-action': 'manipulation',
            'cursor': 'pointer',
            '-webkit-user-select': 'none',
            '-moz-user-select': 'none',
            '-ms-user-select': 'none',
            'user-select': 'none',
            'background-image': 'none',
            'border': '1px solid transparent',
            'border-radius': '4px',
            'color': '#fff',
            'background-color': '#337ab7',
            'border-color': '#2e6da4'
        },
        'input[type=submit]:hover':{
        		'color': '#fff',
            'background-color': '#286090',
            'border-color': '#204d74'
        },
        'input[type=submit]:focus, input[type=submit].focus':{
            'color': '#fff',
            'background-color': '#286090',
            'border-color': '#122b40',
            'text-decoration': 'none',
            'outline': '5px auto -webkit-focus-ring-color',
    				'outline-offset': '-2px'
        }
      },
      // Callback when a token is received from the service
      onTokenSuccess: function (resp) {
        document.querySelector("input[name=token_value]").value = resp.token_value;
        Heartland.Events.removeHandler(document.getElementById('iframes'), 'submit');
        document.getElementById('iframes').submit();
      },
      // Callback when an error is received from the service
      onTokenError: function (resp) {
        alert('There was an error: ' + resp.error.message);
      }
    });

    // Attach a handler to interrupt the form submission
    Heartland.Events.addHandler(document.getElementById('iframes'), 'submit', function (e) {
      // Prevent the form from continuing to the `action` address
      e.preventDefault();
      // Tell the iframes to tokenize the data
      hps.Messages.post(
        {
          accumulateData: true,
          action: 'tokenize',
          message: '<?php echo $this->getSetting('public_api_key'); ?>'
        },
        'cardNumber'
      );
    });
  }(document, Heartland));
</script>

<?php require 'footer.php'; ?>

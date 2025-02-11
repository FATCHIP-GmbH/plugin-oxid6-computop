[{assign var="aPaypalConfig" value=$oViewConf->getPayPalExpressConfig()}]
[{assign var="sCurrency" value=$oViewConf->getLedgerCurrency()}]
[{assign var="sMerchantId" value=$aPaypalConfig.computop.merchantId}]
[{assign var="buttonIdRendered" value=$buttonId|cat:"_is_rendered"}]

<div class="paypal_button [{$buttonclass}] nonexpress" id="[{$buttonId}]"></div>
<script type="text/javascript"
        src="https://www.paypal.com/sdk/js?client-id=[{$aPaypalConfig.paypal.clientId}]&merchant-id=[{$aPaypalConfig.paypal.merchantId}]&currency=[{$sCurrency}]&intent=[{$aPaypalConfig.paypal.intent}]&disable-funding=giropay,sofort,sepa,card"
        data-partner-attribution-id="[{$aPaypalConfig.computop.partnerAttributionId}]"></script>

[{capture assign="fatchip_computop_script_paypalbutton"}]

      if (typeof [{$buttonIdRendered}] === "undefined" || [{$buttonIdRendered}] !== true) {

          var [{$buttonIdRendered}];
          let active = [{$aPaypalConfig.paypal.active}];
          let mid = "[{$sMerchantId}]";
          let payid;
          if (active) {
              paypal.Buttons({
                  createOrder: function (data, actions) {
                      return fetch('[{$aPaypalConfig.computop.actions.urls.createOrder nofilter}]')
                          .then(function(res) {
                              return res.text();
                          })
                          .then(function (orderData) {
                              let qData = new URLSearchParams(orderData);
                              payid = qData.get('PayID');
                              return qData.get('orderid');
                          });
                  },
                  onApprove: async function (data, actions) {
                      var rd = "MerchantId=" + mid + "&PayId=" + payid + "&OrderId=" + data.orderID;
                      const form = document.createElement('form');
                      form.method = 'POST';
                      form.action = 'https://www.computop-paygate.com/cbPayPal.aspx?rd=' + window.btoa(rd);
                      form.style.display = 'none';
                      document.body.appendChild(form);
                      form.submit();
                      /**
                       let onApproveParams = new URLSearchParams([
                       ['merchantId', mid],
                       ['payId', payid],
                       ['orderId', data.orderID]
                       ]);
                       await fetch('[{ $aPaypalConfig.computop.actions.urls.onApprove nofilter }]&' + onApproveParams.toString());
                       **/
                  },
                  onCancel: function (data, actions) {
                      var rd = "MerchantId=" + mid + "&PayId=" + payid + "&OrderId=" + data.orderID;
                      const form = document.createElement('form');
                      form.method = 'POST';
                      form.action = "https://www.computop-paygate.com/cbPayPal.aspx?rd=" + window.btoa(rd) + "&ua=cancel&token=" + data.orderID;
                      form.style.display = 'none';
                      document.body.appendChild(form);
                      form.submit();
                  },
                  onError: function (data, actions) {
                      var rd = "MerchantId=" + mid + "&PayId=" + payid + "&OrderId=" + data.orderID;
                      const form = document.createElement('form');
                      form.method = 'POST';
                      form.action = "https://www.computop-paygate.com/cbPayPal.aspx?rd=" + window.btoa(rd) + "&ua=cancel&token=" + data.orderID;
                      form.style.display = 'none';
                      document.body.appendChild(form);
                      form.submit();
                  }
              }).render('#[{$buttonId}]');
          }
          [{$buttonIdRendered}] = true;
      }
[{/capture}]
[{oxscript add=$fatchip_computop_script_paypalbutton priority=11}]

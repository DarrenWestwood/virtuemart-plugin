<?php
defined ('_JEXEC') or die();

/**
 * @author Blockonomics
 * @version $Id$
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 2004-Copyright (C) 2004 - 2018 Virtuemart Team. All rights reserved.   - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

?>
<div ng-app="shopping-cart-demo" ng-init="progress='100';address='<?php echo $viewData['address'] ?>';order.status='<?php echo $viewData['status'] ?>';timestamp='<?php echo $viewData['timestamp'] ?>';order.order_id='<?php echo $viewData['order_id'] ?>';order.value='<?php echo  $viewData['value'] ?>';order.currency='<?php echo  $viewData['currency'] ?>';order.satoshi='<?php echo $viewData['satoshi'] ?>';finish_order='<?php echo  $viewData['complete_url'] ?>'">
  <div ng-controller="CheckoutController">

  <?php if ($viewData['alt_payments'] == 1) : ?>
   <div class="bnomics-order-container" style="max-width: 800px;">
   <?php else : ?>
   <div class="bnomics-order-container" style="max-width: 600px;">
  <?php endif;?>
      <!-- Heading row -->
      <div class="bnomics-order-heading">
        <div class="bnomics-order-heading-wrapper">
          <div class="bnomics-order-id">
            <span class="bnomics-order-number" ng-cloak> <?php echo 'Order #'; ?>{{order.order_id}}</span>
            <span class="alignright ng-cloak bnomics-time-left" ng-hide="order.status != -1 || altcoin_waiting">{{clock*1000 | date:'mm:ss' : 'UTC'}}</span>
          </div>

          <div ng-cloak ng-hide="order.status != -1 || altcoin_waiting" class="bnomics-progress-bar-wrapper">
            <div class="bnomics-progress-bar-container">
              <div class="bnomics-progress-bar" style="width: {{progress}}%;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Amount row -->
      <div class="bnomics-order-panel">
        <div class="bnomics-order-info">

          <div class="bnomics-bitcoin-pane" ng-hide="altcoin_waiting">
            <!-- Order Status -->
            <h4 class="bnomics-order-status-title" ng-show="order.status != -1" for="invoice-amount" style="margin-top:15px;" ng-cloak><?php echo "Status"; ?></h4>
            <div class="bnomics-order-status-wrapper">
              <h4 class="bnomics-order-status-title" ng-show="order.status == -1" ng-cloak ><?php echo "To pay, send exact amount of BTC to the given address"; ?></h4>
              <span class="warning bnomics-status-warning" ng-show="order.status == -3" ng-cloak><?php echo "Payment Expired (Use browser back button and try again)"; ?></span>
              <span class="warning bnomics-status-warning" ng-show="order.status == -2" ng-cloak><?php echo "Payment Error"; ?></span>
              <span ng-show="order.status == 0" ng-cloak><?php echo "Unconfirmed"; ?></span>
              <span ng-show="order.status == 1" ng-cloak><?php echo "Partially Confirmed"; ?></span>
              <span ng-show="order.status >= 2" ng-cloak ><?php echo "Confirmed"; ?></span>
            </div>

            <div class="bnomics-btc-info">
              <!-- QR and Amount -->
              <div class="bnomics-qr-code">
                <h5 class="bnomics-qr-code-title" for="btn-address"><?php echo "Bitcoin Address"; ?></h5>
                <a href="bitcoin:{{order.address}}?amount={{order.satoshi/1.0e8}}">
                  <qrcode data="bitcoin:{{order.address}}?amount={{order.satoshi/1.0e8}}" size="160" version="6">
                    <canvas class="qrcode"></canvas>
                  </qrcode>
                </a>
                <h5 class="bnomics-qr-code-hint"><?php echo "Click on the QR code above to open in wallet";?></h5>
              </div>

              <!-- BTC Amount -->
              <div class="bnomics-amount">
                <h4 class="bnomics-amount-title" for="invoice-amount"><?php echo "Amount";?></h4>
                <div class="bnomics-amount-wrapper">
                  <span ng-show="{{order.satoshi}}" ng-cloak>{{order.satoshi/1.0e8}}</span>
                  <small>BTC</small> ⇌
                  <span ng-cloak>{{order.value}}</span>
                  <small ng-cloak>{{order.currency}}</small>
                </div>
              </div>
            </div>
			
            <!-- Bitcoin Address -->
            <div class="bnomics-address">
              <input class="bnomics-address-input" type="text" ng-value="order.address" readonly="readonly">
            </div>
            <div class="bnomics-powered-by">
              <?php echo "Powered by " . "Blockonomics"; ?>
            </div>

          </div>

          <?php if ($viewData['alt_payments'] == 1) : ?>
          <div class="bnomics-altcoin-pane" ng-style="{'border-left': (altcoin_waiting)?'none':''}">

            <div ng-hide="altcoin_waiting" ng-cloak>
              <h4 class="bnomics-altcoin-hint"> <?php echo 'OR you can '; ?></h4>
              <div class="bnomics-altcoin-button-wrapper">
                <a ng-click="pay_altcoins()" href=""><img  style="margin: auto;" src="https://shapeshift.io/images/shifty/small_dark_altcoins.png"  class="ss-button"></a>
                <div class="bnomics-altcoin-info-wrapper">
                  <h5 class="bnomics-altcoin-info"><?php echo 'Ethereum, Bitcoin Cash, Dash and many others supported'; ?></h5>
                </div>
              </div>
            </div>

            <div class="bnomics-altcoin-waiting" ng-show="altcoin_waiting" ng-cloak>
              <h4 class="bnomics-altcoin-waiting-info"><?php echo 'Waiting for BTC payment from shapeshift altcoin conversion ' ?></h4>
              <div class="bnomics-spinner"></div>
              <h4 class="bnomics-altcoin-cancel"><a href="" ng-click="altcoin_waiting=false"> Click here</a> to cancel and go back </h4>
            </div>

          </div>
          <?php endif ?>

        </div>
      </div>
    </div>
    <script>var blockonomics_time_period=<?php echo  intval($viewData['timer']) ?>;</script>
    <script src="<?php echo  JROUTE::_(JURI::root() . 'plugins/vmpayment/blockonomics/js/angular.min.js');?>"></script>
    <script src="<?php echo   JROUTE::_(JURI::root() . 'plugins/vmpayment/blockonomics/js/angular-resource.min.js');?>"></script>
    <script src="<?php echo   JROUTE::_(JURI::root() . 'plugins/vmpayment/blockonomics/js/app.js');?>"></script>
    <script src="<?php echo   JROUTE::_(JURI::root() . 'plugins/vmpayment/blockonomics/js/angular-qrcode.js');?>"></script>
    <script src="<?php echo   JROUTE::_(JURI::root() . 'plugins/vmpayment/blockonomics/js/vendors.min.js');?>"></script>
    <script src="<?php echo   JROUTE::_(JURI::root() . 'plugins/vmpayment/blockonomics/js/reconnecting-websocket.min.js');?>"></script>
  </div>
</div>




<style>
  /* ----- Payment Page Styles ------*/
[ng\:cloak],
[ng-cloak],
[data-ng-cloak],
[x-ng-cloak],
.ng-cloak,
.x-ng-cloak {
  display: none !important;
}

.warning {
  color: rgb(239, 121, 79);
}

@keyframes rotate {
  0% {
    transform: perspective(120px) rotateX(0deg) rotateY(0deg);
  }
  50% {
    transform: perspective(120px) rotateX(-180deg) rotateY(0deg);
  }
  100% {
    transform: perspective(120px) rotateX(-180deg) rotateY(-180deg);
  }
}

@keyframes background {
  0% {
    background-color: #27ae60;
  }
  50% {
    background-color: #9b59b6;
  }
  100% {
    background-color: #c0392b;
  }
}

.bnomics-order-container {
  margin: auto;
  padding: 30px;
}

.bnomics-order-container h4 {
  margin: 10px 0;
  font-weight: normal;
}

.bnomics-order-container h5 {
  margin: 15px 0;
  letter-spacing: 1px;
  font-weight: normal;
  text-transform: unset;
}

.bnomics-progress-bar-container {
  position: relative;
  overflow: hidden;
  margin: 20px 0;
  width: 100%;
  height: 7px;
  background: #ddd;
}

.bnomics-progress-bar-container .bnomics-progress-bar {
  position: absolute;
  top: 0;
  left: 0;
  height: 7px;
  background: #666;
}

.bnomics-order-info {
  display: flex;
}

.bnomics-order-info > div {
  padding-left: 15px;
  padding-right: 15px;
  padding-top: 30px;
  padding-bottom: 0;
}

.bnomics-order-info .bnomics-bitcoin-pane {
  flex: 2;
}

.bnomics-bitcoin-pane .bnomics-btc-info {
  display: flex;
  text-align: center;
}

.bnomics-btc-info .bnomics-qr-code {
  flex: 1;
}

.bnomics-btc-info .bnomics-amount {
  flex: 1;
}

.bnomics-altcoin-pane {
  display: flex;
  align-items: center;
  flex: 1;
  border-left: 2px ridge;
  text-align: center;
}

.bnomics-address input {
  margin-top: 15px;
  width: 100%;
  border: 1px solid grey;
  background-color: unset;
  box-shadow: none;
  text-align: center;
}

.bnomics-spinner {
  margin: auto;
  width: 60px;
  height: 60px;
  animation: rotate 1.4s infinite ease-in-out, background 1.4s infinite ease-in-out alternate;
}

.altcoin-td {
  width: 35%;
  border-left: 2px ridge;
}

.bnomics-powered-by {
  text-align: center;
  margin-top: 1.6em;
  font-size: 0.8em;
  padding: 0;
}

/* ---- Mobile -----*/
@media screen and (max-width: 800px) {
  .bnomics-btc-info {
    display: block !important;
  }

  .bnomics-order-container {
    text-align: center;
  }

  .bnomics-order-container h5 {
    margin: 10px 0;
  }

  .bnomics-altcoin-pane {
    display: block;
    border-top: 2px ridge;
    border-left: none;
  }

  .bnomics-order-info {
    display: block;
  }
}

</style>




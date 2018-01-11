<?php

namespace App\Http\Controllers;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use App\Http\Helper\PayPalHelper;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Session;
use App\Http\Models\Carts;
use App\Http\Models\Orders;
use App\Http\Models\PaypalTransactionLogs;
use App\Http\Helper\Helper;
use App\Http\Models\OrdersProductItems;
use DB;


use Illuminate\Http\Request;

class PayPalController extends Controller
{
    public $papal_transaction_log = null;

    function create(Request $request)
    {
        $apiContext = PayPalHelper::getApiContext();
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $carts = Session::get("checkouts");
        $check_sign = md5(json_encode($carts) . config("app.key"));
        $sign = $request->input("sign");
        if ($check_sign != $sign) {
            return response()->json(["status" => false, "errors" => "sign errors"]);
        }
        $business_id = $this->getBusinessId();

        list($result, $total_price_cny) = Carts::FormatProduct($carts, $business_id);
        $exchange_rate = 0.1538;
        list($result, $goods_total_price_usd) = Helper::CartsUSDFormat($result, $exchange_rate);
        $itemList = new ItemList();

        foreach ($result as $key => $value) {
            $item1 = new Item();
            $item1->setName($value["productName"])
                ->setCurrency('USD')
                ->setQuantity($value["quantity"])
                ->setSku($value["id"])// product_id与skuCode Similar to `item_number` in Classic API
                ->setPrice($value["price_usd"]);
            $itemList->addItem($item1);
        }

        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $tax = round($goods_total_price_usd * 0.029 + 0.3, 2);
        $shipping_method = Session::get("select_shipping_method");

        $shipping_method_charge = isset($shipping_method["totalChargeUSD"]) ? $shipping_method["totalChargeUSD"] : 0;
        $total_price = $tax + $goods_total_price_usd + $shipping_method_charge;
        $details = new Details();
        $details->setShipping($shipping_method_charge)
            ->setTax($tax)
            ->setSubtotal($goods_total_price_usd);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($total_price)
            ->setDetails($details);

        // ### Payee
        // Specify a payee with that user's email or merchant id
        // Merchant Id can be found at https://www.paypal.com/businessprofile/settings/
        $payee = new Payee();
        $payee->setEmail("729357-facilitator@qq.com");

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it.
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setPayee($payee)
            ->setInvoiceNumber(uniqid());

        // ### Redirect urls
        // Set the urls that the buyer must be redirected to after
        // payment approval/ cancellation.
        $baseUrl = $request->getSchemeAndHttpHost();
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/paypal/execute-payment")
            ->setCancelUrl("$baseUrl/paypal/execute-payment?success=0");
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        $payment->create($apiContext);
        //创建订单信息
        $users = $request->session()->get("users");
        $member_id = $users["member_id"];
        $shipping_address = Session::get("shipping_address");

        try {

            $rand_member_id = strlen($member_id) > 5 ? substr($member_id, -5) : sprintf("%05d", $member_id);
            $order_id = date("ymdHis") . $rand_member_id . rand(10, 99);
            $total_price_cny = $total_price_cny;
            $total_price_usd = $goods_total_price_usd;
            $delivery_method = "直发";
            $sending_route = 1;
            //物流id选择
            $shipping_methods_id = $shipping_method["productId"];
            $shipping_methods_name = $shipping_method["productEnName"];
            $shipping_methods_price_cny = $shipping_method["totalChargeUSD"];
            $account_address_id = $shipping_address["id"];
            $date_time = date("Y-m-d H:i:s");
            DB::beginTransaction();
            try {
                $data = [
                    "order_id" => $order_id,
                    "business_id" => $business_id,
                    "member_id" => $member_id,
                    "total_price_cny" => $total_price_cny,
                    "total_price_usd" => $total_price_usd,
                    "remarks" => "",
                    "delivery_method" => $delivery_method,
                    "sending_route" => $sending_route,
                    "shipping_methods_id" => $shipping_methods_id,
                    "shipping_methods_name" => $shipping_methods_name,
                    "shipping_methods_price_cny" => $shipping_methods_price_cny,
                    "account_address_id" => $account_address_id,
                    "status" => 0,
                    "status_msg" => "",
                    "created_at" => $date_time,
                    "updated_at" => $date_time
                ];
                Orders::insert($data);

                $payment_id = $payment->id;
                $hash = md5($payment_id);
                $transaction_data = [
                    "order_id" => $order_id,
                    "payment_id" => $payment_id,
                    "hash" => $hash,
                    "status" => 0
                ];
                PaypalTransactionLogs::insert($transaction_data);
                //获取创建订单
                $transaction = $payment->getTransactions();

                $transactions_result = $transaction[0]->item_list->items;
                $product_items = [];
                if (is_array($transactions_result)) {
                    foreach ($transactions_result as $key => $value) {
                        $id = $value->sku;
                        $product_info = $result[$id];
                        $items["order_id"] = $order_id;
                        $items["business_id"] = $business_id;
                        $items["product_id"] = $product_info["product_id"];
                        $items["spuCode"] = $product_info["spu_code"];
                        $items["skuCode"] = $product_info["sku_code"];
                        $items["platform"] = $product_info["platform"];
                        $items["price_cny"] = $product_info["price"];  //淘宝价格
                        $items["price_usd"] = $value->price;         //售出的美元价格
                        $items["quantity"] = $value->quantity;
                        $product_items[] = $items;
                    }
                }
                OrdersProductItems::insert($product_items);
            } catch (\Exception $e) {
                DB::rollBack();
            }
            DB::commit();
        } catch (\Exception $e) {
            return response()->json(["status" => false, "errors" => "Failed to create order Please try again later"]);
        }
        return response()->json(["status"=>true,"data"=>$payment->toArray()]);
    }

    function executePayment(Request $request)
    {
        // 检查这笔订单是否支付过

        $paymentId = $request->input("paymentID");
        $payer_id = $request->input("payerID");

        $success = $request->input("success");
        if($success === 0){
            return response()->json(["status" => false, "errors" => "The order has been canceled"]);
        }
        $business_id = $this->getBusinessId();

        $this->papal_transaction_log = PaypalTransactionLogs::where("payment_id", $paymentId)->first();
        if (empty($this->papal_transaction_log)) {
            return response()->json(["status" => false, "errors" => "paymentId is errors"]);
        }
        if ($this->papal_transaction_log->status == 1) {
            return response()->json(["status" => false, "errors" => "This order has been paid successfully"]);
        }

        $apiContext = PayPalHelper::getApiContext();
        $paymentId = $paymentId;
        $payment = Payment::get($paymentId, $apiContext);
        if ($payment->getState() == "approved") {
            $this->updateOrder($business_id, $paymentId);
            return response()->json(["status" => true, "errors" => "payment successful"]);
        }
        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);
        try {
            // Execute the payment
            $payment->execute($execution, $apiContext);
            try {
                $payment = Payment::get($paymentId, $apiContext);
            } catch (Exception $ex) {
                return response()->json(["status" => false, "errors" => "Payment failed"]);
            }
        } catch (Exception $ex) {
            return response()->json(["status" => false, "errors" => "Payment failed"]);
        }

        if ($payment->getState() == 'approved') {
            try {
                $this->updateOrder($business_id, $paymentId);
                return response()->json(["status" => true, "errors" => "payment successful"]);
            } catch (\Exception $e) {
                return response()->json(["status" => false, "errors" => "update status failed"]);
            }
        } else {
            return response()->json(["status" => false, "errors" => "Payment failed"]);
        }

        return response()->json(["status" => true, "errors" => "payment successful"]);
    }

    function updateOrder($business_id, $paymentId)
    {
        DB::beginTransaction();
        try {
            $this->papal_transaction_log->status = 1;
            $this->papal_transaction_log->save();
            $order = Orders::where("order_id", $this->papal_transaction_log->order_id)->where("business_id", $business_id)->first();
            $order->third_serial_number = $paymentId;
            $order->status = 1;
            $order->updated_at = date("Y-m-d H:i:s");
            //更新表
            $order->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }


}

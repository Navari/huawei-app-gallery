# navari/huawei-app-gallery


PHP library to verify and confirm Huawei AppGallery Inapp Purchases.


## Installation
```shell
composer require navari/huawei-app-gallery
```

##Change 

## Example
```php
// Retrieving subcription data

$huawei = new \Navari\Huawei\Huawei($clientId, $clientSecret);
$result = $huawei->verifySubscription('000001788eb505debf94d1fd2bfea4bd6345d499b083ae57866244271fdaf31567f85314075203c4x5452.7.7621', '1617301931486.1C0A5292.7621');
```
Result:
```php
Array (
  [responseCode] => 0,
  [inappPurchaseData] => "{\"autoRenewing\":true,\"subIsvalid\":true,\"orderId\":\"1581789719266.E359BC66.3089\",\"lastOrderId\":\"L1581789719266.E359BC66.3089\",\"packageName\":\"com.huawei.packagename\",\"applicationId\":123456,\"productId\":\"prd2\",\"kind\":2,\"productName\":\"Subscription name\",\"productGroup\":\"0DED5AC93D084C489F94312E217E1DBD\",\"purchaseTime\":1597677768003,\"oriPurchaseTime\":1597677768003,\"purchaseState\":0,\"developerPayload\":\"payload data\",\"purchaseToken\":\"00000173741056a37eef310dff9c6a86fec57efafe318ae478e52d9c4261994d64c8f6fc8ea1abbdx5347.5.3089\",\"purchaseType\":0,\"currency\":\"CNY\",\"price\":50,\"country\":\"CN\",\"subscriptionId\":\"1581789719266.D40972AC.3089\",\"quantity\":1,\"daysLasted\":0,\"numOfPeriods\":1,\"numOfDiscount\":0,\"expirationDate\":1597677948003,\"retryFlag\":1,\"introductoryFlag\":0,\"trialFlag\":0,\"renewStatus\":1,\"renewPrice\":50,\"cancelledSubKeepDays\":30,\"payOrderId\":\"WX123456789ce8e23ee927\",\"payType\":\"17\",\"confirmed\":1}"
)

```

#Error Codes

Error codes documented in the [Huawei docs](https://developer.huawei.com/consumer/en/doc/development/HMSCore-References-V5/server-error-code-0000001050166248-V5)

# Changelog

Changes are documented in the [releases page](https://github.com/navari/huawei-app-gallery/releases).

# License

The library is open-sourced software licensed under the [MIT License](https://github.com/navari/huawei-app-gallery/blob/main/LICENSE).

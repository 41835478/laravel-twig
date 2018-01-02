   
*   商户相关接口
        
    *   [1.1 商户注册接口](#1.1)
    *   [1.2 二级域名检查是否可用接口](#1.2)
    *   [1.3 检查注册的商户邮箱是否可用](#1.3)
                
*  商品相关
    *   [2.1 增加商品到我的店铺](#2.1)

        


## buckydrop 接口说明

### 接口使用说明
* buckydrop 接口地址 
  ```
  http://10.10.11.121:801  //测试地址 （实际请求域名根据商户的返回的二级域名）
  ```
* 签名认证说明
* 凡POST PUT 方法接口都要计算sign签名 5uFT8SvHU4I2YHBzwMHnkhJH0y6fMP0fCqy2lhQRyI840&*(Pz8UbPxmeLsRHr
* 签名sign计算方法：把数组进行字典排序，然后组成字符串，最后加上key得到str，计算步骤如下
```
$data = [
    'b' => 'BBB',
    'a' => 'AAA',
    'c' => 'CCC'
];
$data['nonce'] = '123abc';  //加入随机数 必传字段
ksort($data);               //按照键名排序
//加入key组成字符串
$str = "a=AAA&b=BBB&c=CCC&nonce=123abc&key=API_KEY";
$data['sign'] = md5($str);
```
* 接口返回
```
{
  status:true
  data:{},
  errors:"",
  "msg":"成功的时提示"
}
status 必须返回 为请求状态标识 true 表示请求成功  false 表示请求失败
data 表示请求返回的数据 部分 post 返回空 不返回此字段
errors 错误时返回此字段 后面跟错误的详细信息
msg post put delte 操作成功的返回提示
本文档中的接口均遵循本规范
认证头信息 自定义头
自定义头类型http     自定义头值     
authorization      Bearer $token (Bearer 与token一个空格) 
```


<h3 id="1.0">设备参数字段</h3>
       
<h3 id="1.0">必传字段</h3>

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| version_code | string or int |   当前安装的版本号   | 否 (device_type 1,2 ,4,5必传此字段)
          
```
 
		    		
```

          
<h3 id="1.1">1.1 商户注册接口</h3>

* 接口地址 /api/v1/business/create
* 请求方法 POST
* 暂时不签名 
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| email  | string |  注册的邮箱 |是   
| password  | string |  密码 |是   
| domain  | string |  二级域名字符串 | 否  

请求头信息
```
{
	"email":"346006742@qq.com",
	"password":"123456",
	"domain":"china",
}
```

* 接口返回 

`
```
  {
    "status": true 表示成功
    "data":{
       "domain":"http://china.buckydrop.com",   // 后面发起请求的域名
       "business_id":111,                       //商户id
       "token":"xxxxxxxxxxxxxxxxxxxxxxxxxxx"  //接口请求令牌    
     }
  }
```


<h3 id="1.2">1.2 二级域名检查是否可用接口 </h3>

* 接口地址 /api/v1/business/check_domain
* 请求方法 GET 
* 参数 无
* 暂时不签名 

* 接口返回

```
{
  "status": true, // true 表示可用 false 不可用
}   
```
    

<h3 id="1.3">1.3  检查注册的商户邮箱是否可用</h3>

* 接口地址 /api/v1/business/check_email
* 请求方法 GET
* 参数 无


* 接口返回

```
{
  "status": true, // true 表示可用 false 不可用
}   
```



<h3 id="2.1">2.1 增加商品到我的店铺</h3>

* 接口地址 /api/v1/business/push_store
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json



请求头信息
```
{
  
           "spuCode": "526233176548",
           "productName": "隐形无线蓝牙耳机耳塞式4.1微型迷你超小音乐VIVO苹果7OPPO通用挂",
           "productTitle": null,
           "spuImgs": [
               "//img.alicdn.com/imgextra/i1/1704230059/TB2xLBSoXXXXXcXXpXXXXXXXXXX_!!1704230059.jpg",
               "//img.alicdn.com/imgextra/i1/1704230059/TB2RhXIoXXXXXXWXFXXXXXXXXXX_!!1704230059.jpg",
               "//img.alicdn.com/imgextra/i1/TB1sFR7LpXXXXbcXVXXXXXXXXXX_!!2-item_pic.png"
           ],
           "upAndDown": 1,
           "updateTime": 1513652225865,
           "platform": "TB",
           "skus": [
               {
                   "skuCode": "3132708929544",
                   "sellPrice": 2850,
                   "sellableNum": 972,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 3232484,
                           "propName": "颜色分类",
                           "valueName": "天蓝色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 6536025,
                           "propName": "套餐类型",
                           "valueName": "官方标配"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2vbdojFXXXXbvXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3147483167718",
                   "sellPrice": 5550,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 324570326,
                           "propName": "颜色分类",
                           "valueName": "土豪金"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266789,
                           "propName": "套餐类型",
                           "valueName": "套餐五"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2NcdtjFXXXXaiXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929530",
                   "sellPrice": 4275,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 324570326,
                           "propName": "颜色分类",
                           "valueName": "土豪金"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266785,
                           "propName": "套餐类型",
                           "valueName": "套餐三"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2NcdtjFXXXXaiXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929542",
                   "sellPrice": 5025,
                   "sellableNum": 998,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 324570326,
                           "propName": "颜色分类",
                           "valueName": "土豪金"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266786,
                           "propName": "套餐类型",
                           "valueName": "套餐四"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2NcdtjFXXXXaiXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929536",
                   "sellPrice": 4050,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 324570326,
                           "propName": "颜色分类",
                           "valueName": "土豪金"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266781,
                           "propName": "套餐类型",
                           "valueName": "套餐二"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2NcdtjFXXXXaiXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929540",
                   "sellPrice": 5025,
                   "sellableNum": 998,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 131726,
                           "propName": "颜色分类",
                           "valueName": "象牙白"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266786,
                           "propName": "套餐类型",
                           "valueName": "套餐四"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2Wy0vjFXXXXXNXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929528",
                   "sellPrice": 4275,
                   "sellableNum": 996,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 131726,
                           "propName": "颜色分类",
                           "valueName": "象牙白"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266785,
                           "propName": "套餐类型",
                           "valueName": "套餐三"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2Wy0vjFXXXXXNXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929520",
                   "sellPrice": 3525,
                   "sellableNum": 997,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 3232484,
                           "propName": "颜色分类",
                           "valueName": "天蓝色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266779,
                           "propName": "套餐类型",
                           "valueName": "套餐一"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2vbdojFXXXXbvXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929529",
                   "sellPrice": 4275,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 67782197,
                           "propName": "颜色分类",
                           "valueName": "活力粉"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266785,
                           "propName": "套餐类型",
                           "valueName": "套餐三"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2aV8AjFXXXXcOXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929533",
                   "sellPrice": 4050,
                   "sellableNum": 998,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 7583529,
                           "propName": "颜色分类",
                           "valueName": "皮肤色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266781,
                           "propName": "套餐类型",
                           "valueName": "套餐二"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2sMRHjFXXXXbDXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3147483167716",
                   "sellPrice": 5550,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 131726,
                           "propName": "颜色分类",
                           "valueName": "象牙白"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266789,
                           "propName": "套餐类型",
                           "valueName": "套餐五"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2Wy0vjFXXXXXNXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929541",
                   "sellPrice": 5025,
                   "sellableNum": 999,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 67782197,
                           "propName": "颜色分类",
                           "valueName": "活力粉"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266786,
                           "propName": "套餐类型",
                           "valueName": "套餐四"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2aV8AjFXXXXcOXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3147483167720",
                   "sellPrice": 5550,
                   "sellableNum": 999,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 67782197,
                           "propName": "颜色分类",
                           "valueName": "活力粉"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266789,
                           "propName": "套餐类型",
                           "valueName": "套餐五"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2aV8AjFXXXXcOXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3147483167719",
                   "sellPrice": 5550,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 6507372,
                           "propName": "颜色分类",
                           "valueName": "炫酷黑"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266789,
                           "propName": "套餐类型",
                           "valueName": "套餐五"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd3.alicdn.com/bao/uploaded/i3/1704230059/TB23u4KjFXXXXbXXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929545",
                   "sellPrice": 2850,
                   "sellableNum": 827,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 7583529,
                           "propName": "颜色分类",
                           "valueName": "皮肤色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 6536025,
                           "propName": "套餐类型",
                           "valueName": "官方标配"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2sMRHjFXXXXbDXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3147483167721",
                   "sellPrice": 5550,
                   "sellableNum": 998,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 7583529,
                           "propName": "颜色分类",
                           "valueName": "皮肤色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266789,
                           "propName": "套餐类型",
                           "valueName": "套餐五"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2sMRHjFXXXXbDXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929546",
                   "sellPrice": 2850,
                   "sellableNum": 919,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 131726,
                           "propName": "颜色分类",
                           "valueName": "象牙白"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 6536025,
                           "propName": "套餐类型",
                           "valueName": "官方标配"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2Wy0vjFXXXXXNXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929539",
                   "sellPrice": 5025,
                   "sellableNum": 995,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 7583529,
                           "propName": "颜色分类",
                           "valueName": "皮肤色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266786,
                           "propName": "套餐类型",
                           "valueName": "套餐四"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2sMRHjFXXXXbDXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929534",
                   "sellPrice": 4050,
                   "sellableNum": 998,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 131726,
                           "propName": "颜色分类",
                           "valueName": "象牙白"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266781,
                           "propName": "套餐类型",
                           "valueName": "套餐二"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2Wy0vjFXXXXXNXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929535",
                   "sellPrice": 4050,
                   "sellableNum": 999,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 67782197,
                           "propName": "颜色分类",
                           "valueName": "活力粉"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266781,
                           "propName": "套餐类型",
                           "valueName": "套餐二"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2aV8AjFXXXXcOXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929527",
                   "sellPrice": 4275,
                   "sellableNum": 997,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 7583529,
                           "propName": "颜色分类",
                           "valueName": "皮肤色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266785,
                           "propName": "套餐类型",
                           "valueName": "套餐三"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2sMRHjFXXXXbDXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929549",
                   "sellPrice": 2850,
                   "sellableNum": 888,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 6507372,
                           "propName": "颜色分类",
                           "valueName": "炫酷黑"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 6536025,
                           "propName": "套餐类型",
                           "valueName": "官方标配"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd3.alicdn.com/bao/uploaded/i3/1704230059/TB23u4KjFXXXXbXXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929524",
                   "sellPrice": 3525,
                   "sellableNum": 994,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 324570326,
                           "propName": "颜色分类",
                           "valueName": "土豪金"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266779,
                           "propName": "套餐类型",
                           "valueName": "套餐一"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2NcdtjFXXXXaiXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929547",
                   "sellPrice": 2850,
                   "sellableNum": 952,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 67782197,
                           "propName": "颜色分类",
                           "valueName": "活力粉"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 6536025,
                           "propName": "套餐类型",
                           "valueName": "官方标配"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2aV8AjFXXXXcOXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929523",
                   "sellPrice": 3525,
                   "sellableNum": 990,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 67782197,
                           "propName": "颜色分类",
                           "valueName": "活力粉"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266779,
                           "propName": "套餐类型",
                           "valueName": "套餐一"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2aV8AjFXXXXcOXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929531",
                   "sellPrice": 4275,
                   "sellableNum": 994,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 6507372,
                           "propName": "颜色分类",
                           "valueName": "炫酷黑"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266785,
                           "propName": "套餐类型",
                           "valueName": "套餐三"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd3.alicdn.com/bao/uploaded/i3/1704230059/TB23u4KjFXXXXbXXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929543",
                   "sellPrice": 5025,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 6507372,
                           "propName": "颜色分类",
                           "valueName": "炫酷黑"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266786,
                           "propName": "套餐类型",
                           "valueName": "套餐四"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd3.alicdn.com/bao/uploaded/i3/1704230059/TB23u4KjFXXXXbXXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929537",
                   "sellPrice": 4050,
                   "sellableNum": 997,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 6507372,
                           "propName": "颜色分类",
                           "valueName": "炫酷黑"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266781,
                           "propName": "套餐类型",
                           "valueName": "套餐二"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd3.alicdn.com/bao/uploaded/i3/1704230059/TB23u4KjFXXXXbXXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3147483167717",
                   "sellPrice": 5550,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 3232484,
                           "propName": "颜色分类",
                           "valueName": "天蓝色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266789,
                           "propName": "套餐类型",
                           "valueName": "套餐五"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2vbdojFXXXXbvXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929548",
                   "sellPrice": 2850,
                   "sellableNum": 939,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 324570326,
                           "propName": "颜色分类",
                           "valueName": "土豪金"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 6536025,
                           "propName": "套餐类型",
                           "valueName": "官方标配"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2NcdtjFXXXXaiXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929522",
                   "sellPrice": 3525,
                   "sellableNum": 987,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 131726,
                           "propName": "颜色分类",
                           "valueName": "象牙白"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266779,
                           "propName": "套餐类型",
                           "valueName": "套餐一"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2Wy0vjFXXXXXNXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929538",
                   "sellPrice": 5025,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 3232484,
                           "propName": "颜色分类",
                           "valueName": "天蓝色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266786,
                           "propName": "套餐类型",
                           "valueName": "套餐四"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2vbdojFXXXXbvXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929525",
                   "sellPrice": 3525,
                   "sellableNum": 983,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 6507372,
                           "propName": "颜色分类",
                           "valueName": "炫酷黑"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266779,
                           "propName": "套餐类型",
                           "valueName": "套餐一"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd3.alicdn.com/bao/uploaded/i3/1704230059/TB23u4KjFXXXXbXXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929526",
                   "sellPrice": 4275,
                   "sellableNum": 998,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 3232484,
                           "propName": "颜色分类",
                           "valueName": "天蓝色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266785,
                           "propName": "套餐类型",
                           "valueName": "套餐三"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2vbdojFXXXXbvXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929521",
                   "sellPrice": 3525,
                   "sellableNum": 971,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 7583529,
                           "propName": "颜色分类",
                           "valueName": "皮肤色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266779,
                           "propName": "套餐类型",
                           "valueName": "套餐一"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2sMRHjFXXXXbDXXXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               },
               {
                   "skuCode": "3132708929532",
                   "sellPrice": 4050,
                   "sellableNum": 1000,
                   "productProps": [
                       {
                           "propId": 1627207,
                           "valueId": 3232484,
                           "propName": "颜色分类",
                           "valueName": "天蓝色"
                       },
                       {
                           "propId": 5919063,
                           "valueId": 3266781,
                           "propName": "套餐类型",
                           "valueName": "套餐二"
                       }
                   ],
                   "skuName": null,
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2vbdojFXXXXbvXpXXXXXXXXXX_!!1704230059.png",
                   "marketPrice": 3800
               }
           ],
           "productProps": [
               {
                   "propId": 1627207,
                   "valueId": 3232484,
                   "propName": "颜色分类",
                   "valueName": "天蓝色",
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2vbdojFXXXXbvXpXXXXXXXXXX_!!1704230059.png",
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 1627207,
                   "valueId": 7583529,
                   "propName": "颜色分类",
                   "valueName": "皮肤色",
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2sMRHjFXXXXbDXXXXXXXXXXXX_!!1704230059.png",
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 1627207,
                   "valueId": 131726,
                   "propName": "颜色分类",
                   "valueName": "象牙白",
                   "imgUrl": "//gd4.alicdn.com/bao/uploaded/i4/1704230059/TB2Wy0vjFXXXXXNXpXXXXXXXXXX_!!1704230059.png",
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 1627207,
                   "valueId": 67782197,
                   "propName": "颜色分类",
                   "valueName": "活力粉",
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2aV8AjFXXXXcOXXXXXXXXXXXX_!!1704230059.png",
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 1627207,
                   "valueId": 324570326,
                   "propName": "颜色分类",
                   "valueName": "土豪金",
                   "imgUrl": "//gd2.alicdn.com/bao/uploaded/i2/1704230059/TB2NcdtjFXXXXaiXpXXXXXXXXXX_!!1704230059.png",
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 1627207,
                   "valueId": 6507372,
                   "propName": "颜色分类",
                   "valueName": "炫酷黑",
                   "imgUrl": "//gd3.alicdn.com/bao/uploaded/i3/1704230059/TB23u4KjFXXXXbXXXXXXXXXXXXX_!!1704230059.png",
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 5919063,
                   "valueId": 3266779,
                   "propName": "套餐类型",
                   "valueName": "套餐一",
                   "imgUrl": null,
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 5919063,
                   "valueId": 3266785,
                   "propName": "套餐类型",
                   "valueName": "套餐三",
                   "imgUrl": null,
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 5919063,
                   "valueId": 3266781,
                   "propName": "套餐类型",
                   "valueName": "套餐二",
                   "imgUrl": null,
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 5919063,
                   "valueId": 3266789,
                   "propName": "套餐类型",
                   "valueName": "套餐五",
                   "imgUrl": null,
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 5919063,
                   "valueId": 3266786,
                   "propName": "套餐类型",
                   "valueName": "套餐四",
                   "imgUrl": null,
                   "propNameMap": null,
                   "valueNameMap": null
               },
               {
                   "propId": 5919063,
                   "valueId": 6536025,
                   "propName": "套餐类型",
                   "valueName": "官方标配",
                   "imgUrl": null,
                   "propNameMap": null,
                   "valueNameMap": null
               }
           ],
           "productDetail": null
       }

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "Push to store success"
 }
```


   
*   商户相关接口
        
    *   [1.1 商户注册接口](#1.1)
    *   [1.2 商户登陆接口](#1.2)
    *   [1.3 二级域名检查是否可用接口](#1.3)
    *   [1.4 检查注册的商户邮箱是否可用](#1.4)
    *   [1.5 获取商户设置的地址信息](#1.5)
    *   [1.6 设置商户地址信息](#1.6)
    *   [1.7 获取国家列表接口](#1.7)
    *   [1.8 根据国家获取所有的州或者省](#1.8)

            
*  商品相关
    *   [2.1 商品搜索接口](#2.1)
    *   [2.2 增加商品到我的店铺](#2.2)
    *   [2.3 商品列表及检索接口](#2.3)
    *   [2.4 批量管理商品接口](#2.4)
        
*  文件相关
     *   [3.1 文件上传接口](#3.1)
     *   [3.2 分页返回商户上传的图片](#3.2)

* 商品集合相关
    *   [4.1 创建产品集合](#4.1)
    *   [4.2 分页获取集合的列表](#4.2)
    *   [4.3 获取单个集合的信息](#4.3)
    *   [4.4 集合更新接口](#4.4)
    *   [4.5 批量管理集合接口](#4.5)
        
* 单页集合相关
    *   [5.1 创建单页接口](#5.1)
    *   [5.2 分页获取单页列表](#5.2)
    *   [5.3 获取单个单页的信息](#5.3)
    *   [5.4 单页更新接口](#5.4)
    *   [5.5 批量管理单页接口](#5.5)
    
* blog分类的管理

    *   [6.1 创建分类接口](#6.1)
    *   [6.2 分页获取分类列表](#6.2)
    *   [6.3 获取单个分类的信息](#6.3)
    *   [6.4 分类更新接口](#6.4)
    *   [6.5 批量管理分类接口](#6.5)    
* blog文章管理
        
    *   [7.1 创建博客接口](#7.1)
    *   [7.2 分页获取博客列表](#7.2)
    *   [7.3 获取单个博客的信息](#7.3)
    *   [7.4 博客更新接口](#7.4)
    *   [7.5 批量管理博客接口](#7.5)
            
* 店铺货币设置与收款邮箱设置
   
    *   [8.1 获取支持的货币列表](#8.1)
    *   [8.2 获取店铺设置中货币相关](#8.2)
    *   [8.3 更新店铺设置中货币相关](#8.3)
        
* 店铺运费模板相关

     *   [9.1 获取物流服务商列表](#9.1)
     *   [9.2 批量新增或者更新运费](#9.2)
     *   [9.3 按国家获取设置的运费方式](#9.3)
     *   [9.4 获取单个国家设置的运费方式](#9.4)
     *   [9.5 删除某些国家设置的运费](#9.5)

* 后台导航相关接口
      * [10.1 获取导航列表相关接口](#10.1)
      * [10.2 获取导航中菜单列表](#10.2)
      * [10.3 获取菜单添加中类型列表](#10.3)       
      * [10.4 新增菜单项](#10.4)
      * [10.5 获取单个菜单详情](#10.5)
      * [10.6 更新单个菜单详情](#10.6)
      * [10.7 菜单批量操作](#10.7)
            
            
            
             
                         
    
           
## buckydrop 接口说明

### 接口使用说明
* buckydrop 接口地址 
  ```
  http://10.10.11.128  //测试地址
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
自定义头类型     自定义头值     
authorization Bearer $api_token 
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
* 需要签名 
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
    "data"=>{
       "redirect_url":"http://china.buckydrop.com"    
     }
  }
```


<h3 id="1.2">1.2  商户登陆接口</h3>

* 接口地址 /api/v1/business/login
* 请求方法 GET

### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| email  | string |  注册的邮箱 |是   
| password  | string |  密码 |是   

* 接口返回

```
{
  "status": true
  "data": {
    "api_token": "opyxCHBDU9xEt42Hcj1cgmbq0jmqBISiecC6LixthCwC99eajoM6OzmCJHVL"
    "business_id": 1,
    "has_address":true
  }

```
* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| api_token  |  商户的请求凭证
| has_address  |  false 需要用户先补充地址信息（不能做其他的动作 先设置地址信息）



<h3 id="1.3">1.3 二级域名检查是否可用接口 </h3>

* 接口地址 /api/v1/business/check_domain
* 请求方法 GET 
* 参数 无

* 接口返回

```
{
  "status": true, // true 表示可用 false 不可用
}   
```
    

<h3 id="1.4">1.4  检查注册的商户邮箱是否可用</h3>

* 接口地址 /api/v1/business/check_email
* 请求方法 GET
* 参数 无


* 接口返回

```
{
  "status": true, // true 表示可用 false 不可用
}   
```


<h3 id="1.5">1.5 获取商户设置的地址信息</h3>

* 接口地址 /api/v1/business/address
* 请求方法 GET
* 需要认证头

```
* 接口返回 
{
    "status": true,
    "data": {
        "business_id": 1,
        "first_name": "river",
        "last_name": "wang",
        "street_address": "固戍地铁站",
        "suite": "406",
        "country_id": 0,
        "state_id": 0,
        "city_id": 0,
        "phone_number": "18566221038",
        "website": "http://baidu.com",
        "created_at": null,
        "updated_at": null
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| business_id  |  商户id
| first_name  | 
| last_name  |  
| street_address |  街道地址
| suite  | 门牌号地址
| country_id  | 国家id
| state_id  | 州或者省的id
| city_id | 城市id
| phone_number  | 手机号码
| website  | 商户网站


<h3 id="1.6">1.6 设置商户地址信息</h3>

* 接口地址 /api/v1/business/address
* 请求方法 POST
* 需要认证头

### 参数说明
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| first_name  | string |  名 |是   
| last_name  | string |  姓 |是   
| street_address  | string |  街道地址 |是   
| suite  | string |  门牌号地址 |是
| phone_number  | string |  电话号码 |是
| country_id  | int |  国家id |否
| state_id  | int |  州id |否
| city_id  | int |  城市id |否
| website  | string |  商户的网站 | 否  

##请求body参数

```
{
        "first_name": "river",
        "last_name": "wang",
        "street_address": "固戍地铁站",
        "suite": "406",
        "country_id": 0,
        "state_id": 0,
        "city_id": 0,
        "phone_number": "18566221038",
        "website": "http://baidu.com"
}
```


```

* 接口返回设置成功的信息
{
    "status": true,
    "data": {
        "business_id": 1,
        "first_name": "river",
        "last_name": "wang",
        "street_address": "固戍地铁站",
        "suite": "406",
        "country_id": 0,
        "state_id": 0,
        "city_id": 0,
        "phone_number": "18566221038",
        "website": "http://baidu.com",
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| business_id  |  商户id
| first_name  | 
| last_name  |  
| street_address |  街道地址
| suite  | 门牌号地址
| country_id  | 国家id
| state_id  | 州或者省的id
| city_id | 城市id
| phone_number  | 手机号码
| website  | 商户网站



<h3 id="1.7">1.7 获取国家列表接口 </h3>

* 接口地址 /api/v1/geo/country
* 请求方法 GET 
* 参数 无

* 接口返回

```
  {
      "status": true,
      "data": {
          "1": "Afghanistan",
          "2": "Aland lslands",
          "3": "Albania",
      }
  }
```

<h3 id="1.8">1.8 根据国家获取所有的州或者省 </h3>

* 接口地址 /api/v1/geo/state
* 请求方法 GET 
### 参数说明
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| country_id  | int |  国家id |是   



* 接口返回

```
{
    "status": true,
    "data": {
        "has_state": false,
        "data": {
            "1": "Herat",
            "2": "Kabul",
            "3": "Kandahar",
            "4": "Mazar-i Sharif"
        }
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| has_state  |  如果true data.data 返回的是州或者省数据需要动态获取城市数据 false 返回的城市列表数据
| data.data  |  根据返回的状态值 州列表或者 城市列表





<h3 id="2.1">2.1 商品搜索接口</h3>

* 接口地址 /api/v1/products/search
* 请求方法 GET

### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| keywords  | string |  搜索的关键字 |是 
| to_page  | int |  第几页 | 否 
| per_pagesize  | int |  每页大小 | 否
| free_shipment  | int |  是否免国内运费 （1免国内运费）| 否
| start_price  | string |  价格区间开始价格 单位元| 否
| end_price  | string |  价格区间结束价格 单位元| 否
| platform  | string | 平台字段,不填取全部的平台(京东、淘宝、天猫);1:淘宝;2:天 猫;3京东；10:淘宝联盟。eg:1,2 代表 淘宝+天猫;1,3；代表淘宝 +京东 | 否
| lang_type | int| 1中文，2英文  | 否

* 接口返回

```
{
    "status": true,
    "data": {
        "keyword": "儿童演出服",
        "rowCount": 34,
        "totalCount": 170,
        "pageSize": 34,
        "pageNo": 1,
        "skip": 0,
        "direction": 0,
        "needsort": false,
        "totalPage": 5,
        "hasPreviousPage": false,
        "hasNextPage": true,
        "userId": "0",
        "priceFrom": 0,
        "priceTo": 99999999999.99,
        "datas": [
            {
                "goodsId": "535074476365",
                "goodsUrl": "//a.m.taobao.com/i535074476365.htm?rn=0833cb1349cff194889253ff1467e5ae&sid=a04442db9f4fb13c0f5cc2c318b3fb8c",
                "imgUrl": "http://g.search2.alicdn.com/img/bao/uploaded/i4/i2/2672664199/TB2VtYpadLO8KJjSZFxXXaGEVXa_!!2672664199.jpg_400x400.jpg",
                "price": 53,
                "title": "原版第七届小荷风采儿童舞蹈笋儿尖尖演出服十一中小学舞蹈表演服",
                "shop": {
                    "providerType": "TB",
                    "shopId": "2672664199",
                    "shopName": "曹县金达网络科技有限公司",
                    "shopLoc": "菏泽"
                },
                "popularity": 0,
                "statusText": "0已付款",
                "providerType": "TB",
                "platformName": "淘宝网"
            }
            
        ],
        "toPage": 1,
        "perPageSize": 40,
        "listType": "SELF_SEARCH"
    }
}
```

<h3 id="2.2">2.2 增加商品到我的店铺</h3>

* 接口地址 /api/v1/products/push_store
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| goods_id  | bigInt |  搜索中 datas.goodsId 返回值  |是
| provider_type  | bigInt |  搜索中 datas.providerType 返回值  |是   
| language  | smallint |  1中文，2英文  | 否 默认中文

请求头信息
```
{
 "goods_id":"535074476365",
 "provider_type":"TB",
 "language":1
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



<h3 id="2.3">2.3 商品列表及检索接口</h3>

* 接口地址 /api/v1/products/index
* 请求方法 GET

### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| product_name  | string |  产品名称 | 否 
| up_and_down  | smallint | 是否下架 （ 0 未知 -1 下架 1 上架） | 否
| per_pagesize  | smallint | 每页的个数  | 否
| page  | smallint | 第几页  | 否

* 接口返回

```
{
    "status": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "product_id": 5,
                "spu_img": "//gd4.alicdn.com/imgextra/i2/2672664199/TB2VtYpadLO8KJjSZFxXXaGEVXa_!!2672664199.jpg",
                "product_name": "原版第七届小荷风采儿童舞蹈笋儿尖尖演出服十一中小学舞蹈表演服",
                "up_and_down": -1,
                "platform_name": "TB",
                "source_url": "https://item.taobao.com/item.htm?id=535074476365"
            },
            {
                "product_id": 6,
                "spu_img": "//img.alicdn.com/bao/uploaded/i2/3058610373/TB1vgNVrlUSMeJjy1zjXXc0dXXa_!!0-item_pic.jpg",
                "product_name": "儿童迷彩服军训军装演出服中小学生户外海陆空特种兵表演服装套装",
                "up_and_down": -1,
                "platform_name": "TB",
                "source_url": "https://item.taobao.com/item.htm?id=554730673540"
            },
            {
                "product_id": 7,
                "spu_img": "//img.alicdn.com/bao/uploaded/i2/3058610373/TB1vgNVrlUSMeJjy1zjXXc0dXXa_!!0-item_pic.jpg",
                "product_name": "儿童迷彩服军训军装演出服中小学生户外海陆空特种兵表演服装套装",
                "up_and_down": 1,
                "platform_name": "TB",
                "source_url": "https://item.taobao.com/item.htm?id=554730673540"
            }
        ],
        "total": 3,
        "per_pagesize": 3,
        "total_page": 1
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| current_page  |  当前页
| total  |  总条数
| per_pagesize  |  每页个数
| total_page |  总页数
| data.data.product_id  | 系统商品id
| data.data.spu_img  | 封面图
| data.data.product_name  | 标题
| data.data.up_and_down  | 状态  1 上架 0 未知 -1 下架
| data.data.platform_name  | 平台名称
| data.data.source_url  | 资源地址



<h3 id="2.4">2.4 批量管理商品接口</h3>

* 接口地址 /api/v1/products/action
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| product_id_list  | 数组 |  需要操作的产品id列表  |是 
| type  | smallint |  1 上架 2 下架 3 删除（删除有风险） | 是

请求body
```
{
	"type":2,
	"product_id_list":[5,6]
}

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "Push to store success" // msg 返回 在接口规则中有说明
 }
```


<h3 id="3.1">3.1 单文件上传接口</h3>

* 接口地址 /api/v1/images/upload
* 请求方法 POST
* 需要认证头
* 接口请求头 form-data

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| file  | file |  需要上传的文件  | 是 

* 接口返回 

```
{
    "status": true,
    "data": {
        "image_url": "http://10.10.11.121:8080/images/1/7d774101f5047fbb43107211838c7e64.pic_026.jpg"
    }
}

```


<h3 id="3.2">3.2 分页返回商户上传的图片</h3>

* 接口地址 /api/v1/images/index
* 请求方法 GET

### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| per_pagesize  | smallint | 每页的个数  | 否
| page  | smallint | 第几页  | 否

* 接口返回

```
{
    "status": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "image_url": "http://10.10.11.121:808050a44bb4710e61869118e8f398b43883.pic_026.jpg"
            }
        ],
        "total": 3,
        "per_pagesize": 1,
        "total_page": 3
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| current_page  |  当前页
| total  |  总条数
| per_pagesize  |  每页个数
| total_page |  总页数
| data.data.id  | 返回的图片id
| data.data.image_url  | 返回的图片url地址


<h3 id="4.1">4.1 创建产品集合</h3>

* 接口地址 /api/v1/collections
* 请求方法 POST
* 需要认证头
* 接口请求头 form-data

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  集合名称  | 是
| description  | string |  集合描述  | 否
| file  | file or string |  上传的文件或者网络地址  | 否
| product_id_list  | array |  添加的产品 （表单提交 product_id_list[] 这种格式）  | 否


* 接口返回 

```
{
    "status": true,
    "data":{
        "collection_id":1
    }
}

```


<h3 id="4.2">4.2 分页获取集合的列表</h3>

* 接口地址 /api/v1/collections
* 请求方法 GET
* 需要认证头


### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| per_pagesize  | smallint | 每页的个数  | 否
| page  | smallint | 第几页  | 否

* 接口返回

```
{
    "status": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "collection_id": 3,
                "title": "最新产品",
                "cover_images": "http://10.10.11.121:8080/images/20171215065328/6e3474ee25626d9f9db39a12d895412b.pic_017.jpg",
                "products_count": 3
            }
        ],
        "total": 2,
        "per_pagesize": 1,
        "total_page": 2
    }
}

```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| current_page  |  当前页
| total  |  总条数
| per_pagesize  |  每页个数
| total_page |  总页数
| data.data.collection_id  | 集合id
| data.data.title  | 集合的标题
| data.data.cover_images  | 封面图
| data.data.products_count  | 包含的产品数



<h3 id="4.3">4.3 获取单个集合的信息</h3>

* 接口地址 /api/v1/collections/{id}
* 请求方法 GET
* 需要认证头
* {id} 表示占位符 表示需要请求的集合id 


* 接口返回

```
{
    "status": true,
    "data": {
        "id": 3,
        "title": "最新产品",
        "description": "最新产品的描述",
        "cover_images": "http://10.10.11.121:8080/images/20171215065328/6e3474ee25626d9f9db39a12d895412b.pic_017.jpg",
        "product_list": [
            {
                "product_id": 5,
                "product_name": "原版第七届小荷风采儿童舞蹈笋儿尖尖演出服十一中小学舞蹈表演服",
                "spu_img": "//gd4.alicdn.com/imgextra/i2/2672664199/TB2VtYpadLO8KJjSZFxXXaGEVXa_!!2672664199.jpg"
            },
            {
                "product_id": 6,
                "product_name": "儿童迷彩服军训军装演出服中小学生户外海陆空特种兵表演服装套装",
                "spu_img": "//img.alicdn.com/bao/uploaded/i2/3058610373/TB1vgNVrlUSMeJjy1zjXXc0dXXa_!!0-item_pic.jpg"
            },
            {
                "product_id": 7,
                "product_name": "儿童迷彩服军训军装演出服中小学生户外海陆空特种兵表演服装套装",
                "spu_img": "//img.alicdn.com/bao/uploaded/i2/3058610373/TB1vgNVrlUSMeJjy1zjXXc0dXXa_!!0-item_pic.jpg"
            }
        ]
    }
}

```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| data.id  | 集合id
| data.title  | 集合的标题
| data.cover_images  | 封面图
| data.product_list  | 集合包含的产品列表

<h3 id="4.4">4.4 集合更新接口</h3>

* 接口地址 /api/v1/collections/{id}
* 请求方法 POST
* 需要认证头
* 接口请求头 form-data
* {id} 表示占位符 表示需要请求的集合id 

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  集合名称  | 是
| description  | string |  集合描述  | 否
| file  | file or string |  上传的文件或者网络地址  | 否
| product_id_list  | array |  添加的产品 （表单提交 product_id_list[] 这种格式） （如果为空不更改任何产品相关） | 否
| action  | int |  1 新增商品到新的集合（默认） 2 更新（增加的新增 此集合中没有的从原集合中删除） 3 删除的其中的商品  | 否


* 接口返回 

```
{
    "status": true,
    "msg": "Update the collection is successful"
}
```

<h3 id="4.5">4.5 批量管理集合接口</h3>

* 接口地址 /api/v1/collections/action
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| type  | smallint |  1 上架 2 下架 3 删除（删除有风险） | 是
| collection_id_list  | 数组 |  需要操作的产品id列表 类似表单提交 name都赋值 collection_id_list[]   |是 

请求body
```
{
	"type":2,
	"collection_id_list":[5,6]
}

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "Push to store success" // msg 返回 在接口规则中有说明
 }
```


<h3 id="5.1">5.1 创建单页接口</h3>

* 接口地址 /api/v1/pages
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  集合名称  | 是
| content  | string |  单页内容  | 否
| template  | string |  模板值 （ page 或者 page.contact） | 是
| up_and_down  | smallint | 1 发布 2 隐藏  | 是


请求body
```
{
	"title":"这是联系我们",
	"content":"公司介绍",
	"template":"page.contact",
	"up_and_down":1
}
```


* 接口返回 

```
{
    "status": true,
    "data”：{
        "page":11
    }
}

```


<h3 id="5.2">5.2 分页获取单页列表</h3>

* 接口地址 /api/v1/pages
* 请求方法 GET
* 需要认证头


### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| per_pagesize  | smallint | 每页的个数  | 否
| page  | smallint | 第几页  | 否

* 接口返回

```
{
    "status": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "page_id": 1,
                "title": "Supreme",
                "content": "这是一个公司介绍",
                "up_and_down": 1,
                "updated_at": "2017-12-15 13:01:27"
            }
        ],
        "total": 2,
        "per_pagesize": 1,
        "total_page": 2
    }
}

```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| current_page  |  当前页
| total  |  总条数
| per_pagesize  |  每页个数
| total_page |  总页数
| data.data.page_id  | 单页id
| data.data.title  | 单页标题
| data.data.content  | 内容
| data.data.up_and_down  | 状态
| data.data.updated_at  | 最后更新时间



<h3 id="5.3">5.3 获取单个集合的信息</h3>

* 接口地址 /api/v1/pages/{id}
* 请求方法 GET
* 需要认证头
* {id} 表示占位符 表示需要请求的集合id 


* 接口返回

```
{
    "status": true,
    "data": {
        "id": 1,
        "title": "Supreme",
        "content": "这是一个公司介绍",
        "up_and_down": 1,
        "page_title": null,
        "meta_description": null,
        "handle": null
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| id | 单页id
| title  | 单页标题
| content  | 内容
| up_and_down  | 状态


<h3 id="5.4">5.4 单页更新接口</h3>

* 接口地址 /api/v1/pages/{id}
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json
* {id} 表示占位符 表示需要请求的集合id 

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  集合名称  | 是
| content  | string |  单页内容  | 否
| template  | string |  模板值 （ page 或者 page.contact） | 是
| up_and_down  | smallint | 1 发布 2 隐藏  | 是


* 接口返回 

```
{
    "status": true,
    "msg": "Update the collection is successful"
}
```

<h3 id="5.5">5.5 批量管理单页接口</h3>

* 接口地址 /api/v1/pages/action
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| type  | smallint |  1 上架 2 下架 3 删除（删除有风险） | 是
| page_id_list  | 数组 |  需要操作的产品id列表 类似表单提交 name都赋值 page_id_list[]   |是 

请求body
```
{
	"type":2,
	"page_id_list":[5,6]
}

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "xxx操作成功的返回" // msg 返回 可以用于客户端的提示
 }
 
```




<h3 id="6.1">6.1 创建分类接口</h3>

* 接口地址 /api/v1/blogs
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  分类名称  | 是
| handle  | string | 分类 前台模板索引   | 否
| comments  | int |  1 不允许评论（默认） 2 允许但要审核 3不许审核直接发布  | 否



请求body
```
{
	"title":"News",
	"handle":"news",
	"comments":1
}
```


* 接口返回 

```
{
    "status": true,
    "data": {
        "blog_id": 3
    }
}
```

<h3 id="6.2">6.2 分页获取分类列表</h3>

* 接口地址 /api/v1/blogs
* 请求方法 GET
* 需要认证头


### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| per_pagesize  | smallint | 每页的个数  | 否
| page  | smallint | 第几页  | 否

* 接口返回

```
{
    "status": true,
    "data": [
        {
            "blog_id": 3,
            "title": "News",
            "comments": 1
        },
        {
            "blog_id": 2,
            "title": "News1",
            "comments": 1
        }
    ]
}

```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| current_page  |  当前页
| total  |  总条数
| per_pagesize  |  每页个数
| total_page |  总页数
| data.data.blog_id  | 分类id
| data.data.title  | 分类标题
| data.data.comments  | 评论

<h3 id="6.3">6.3 获取单个分类的信息</h3>

* 接口地址 /api/v1/blogs/{id}
* 请求方法 GET
* 需要认证头
* {id} 表示占位符 表示需要请求的blog分类id 


* 接口返回

```
{
    "status": true,
    "data": {
        "title": "News",
        "page_title": "News",
        "id": 3,
        "meta_description": "News",
        "handle": "news-1",
        "comments": 1
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| id | 分类id
| title  | 单页标题
| handle  | seo url地址索引
| comments  | 1 不允许评论（默认） 2 允许但要审核 3不许审核直接发布

<h3 id="6.4">6.4 单页更新接口</h3>

* 接口地址 /api/v1/blogs/{id}
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json
* {id} 表示占位符 表示需要请求的集合id 

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  分类名称  | 是
| handle  | string | 分类 前台模板索引   | 否
| comments  | int |  1 不允许评论（默认） 2 允许但要审核 3不许审核直接发布  | 否


* 接口返回 

```
{
    "status": true,
    "msg": "Update the blogs is successful"
}
```

<h3 id="6.5">6.5 批量管理分类接口</h3>

* 接口地址 /api/v1/pages/action
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| type  | smallint |   3 删除（删除有风险） | 是
| blog_id_list  | 数组 |  需要操作的产品id列表    |是 

请求body
```
{
	"type":3,
	"blog_id_list":[5,6]
}

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "successfully deleted"" // msg 返回 可以用于客户端的提示
 }
 
```

<h3 id="7.1">7.1 创建博客接口</h3>

* 接口地址 /api/v1/article
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  分类名称  | 是
| handle  | string | 分类 前台模板索引   | 否
| blog_id  | int | 分类 id   | 是
| content  | string | 内容  | 是
| up_and_down  | int | 1 显示 2 隐藏  | 是
| featured_image  | string | 封面  | 否
| comments  | int | 1 不允许评论（默认） 2 允许但要审核 3不许审核直接发布  | 是
| description  | string | 文章摘要  | 否


请求body
```
{
    "title":"这是一片新闻",
    "handle":"newsddd'",
    "content":"这是文章内容",
    "blog_id":3,
    "up_and_down":1,
    "featured_image":"http://xxxx.jpg",
    "comments":1,
    "description":1
}
```

```
* 接口返回 

{
    "status": true,
    "data": {
        "article_id": 1
    }
}
```



<h3 id="7.2">7.2 分页获取博客列表</h3>

* 接口地址 /api/v1/article
* 请求方法 GET
* 需要认证头


### 参数
| 参数      |    类型 |  说明 | 是否必须  |
| :-------- | --------:|--------: |:--: |
| per_pagesize  | smallint | 每页的个数  | 否
| page  | smallint | 第几页  | 否

* 接口返回

```
{
    "status": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 3,
                "title": "这是一片新闻",
                "blog_id": 3,
                "blog_title": "News111111",
                "featured_image": "http://xxxx.jpg",
                "author": "riverwang",
                "updated_at": "2017-12-22 07:54:12"
            },
            {
                "id": 2,
                "title": "这是一片新闻11",
                "blog_id": 3,
                "blog_title": "News111111",
                "featured_image": "",
                "author": "riverwang",
                "updated_at": "2017-12-21 13:14:12"
            },
        ],
        "total": 3,
        "per_pagesize": 20,
        "total_page": 1
    }
}
```


<h3 id="7.3">7.3 获取单个博客的信息</h3>

* 接口地址 /api/v1/article/{id}
* 请求方法 GET
* 需要认证头
* {id} 表示占位符 表示需要请求的blog分类id 


* 接口返回

```
{
    "status": true,
    "data": {
        "id": 3,
        "title": "这是一片新闻",
        "blog_id": 3,
        "content": "这是文章内容",
        "comments": 1,
        "description": "",
        "up_to_down": 1,
        "featured_image": "http://xxxx.jpg",
        "author": "riverwang",
        "updated_at": "2017-12-22 07:26:54"
    }
}
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| id | 分类id
| title  | 单页标题
| content  | 内容
| blog_id  | 所属的分类的id
| featured_image  | 封面图
| up_to_down  | 1 发布 2 隐藏



<h3 id="7.4">7.4 博客更新接口</h3>

* 接口地址 /api/v1/article/{id}
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json
* {id} 表示占位符 表示需要请求的集合id 



* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  分类名称  | 是
| handle  | string | 分类 前台模板索引   | 否
| content  | string | 内容  | 是
| up_and_down  | int | 1 显示 2 隐藏  | 是
| featured_image  | string | 封面  | 否
| comments  | int | 1 不允许评论（默认） 2 允许但要审核 3不许审核直接发布  | 是
| description  | string | 文章摘要  | 否


请求demo
```
{
    "title":"这是一片新闻",
    "handle":"newsddd'",
    "content":"这是文章内容11111111111",
    "blog_id":3,
    "up_and_down":1,
    "featured_image":"http://xxxx.jpg",
    "comments":1,
    "description":1
}
```

* 接口返回 
```
{
    "status": true,
    "msg": "Update the article is successful"
}
```


<h3 id="7.5">7.5 批量管理博客接口</h3>

* 接口地址 /api/v1/article/action
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| type  | smallint |   3 删除（删除有风险） | 是
| article_id_list  | 数组 |  需要操作的博客id列表    | 是  

请求body
```
{
	"type":3,
	"article_id_list":[3]
}

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "Shelves success"
 }
```

<h3 id="8.1">8.1 获取支持的货币列表</h3>

* 接口地址 /api/v1/settings/get_currency_list
* 请求方法 GET
* 需要认证头


* 接口返回

```
{
    "status": true,
    "data": [
        {
            "area_id": 3,
            "area_en_name": "USA",
            "currency": "USD",
            "currency_format": "$ {{amount}} USD"
        },
        {
            "area_id": 79,
            "area_en_name": "China",
            "currency": "CNY",
            "currency_format": "¥ {{amount}} CNY"
        },
        {
            "area_id": 24999,
            "area_en_name": "Canada",
            "currency": "CAD",
            "currency_format": "$ {{amount}} CAD"
        }
    ]
}
```
* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| area_id | 所在地区id
| area_en_name  | 所在地区的英文名称
| currency  | 货币简称
| currency_format  | 默认的货币展示格式 {{amount}}表示占位符合 会替换成实际的货币数值  


<h3 id="8.2">8.2 获取店铺设置中货币相关</h3>

* 接口地址 /api/v1/settings/get_currency_list
* 请求方法 GET
* 需要认证头


* 接口返回

```
{
    "status": true,
    "data": {
        "area_id": 3,
        "currency": "USD",
        "currency_format": "$ {{amount}} USD",
        "paypal_email": ""
    }
}
```
* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| area_id | 所在地区id
| currency  | 货币简称
| currency_format  | 默认的货币展示格式 {{amount}}表示占位符合 会替换成实际的货币数值
| paypal_email  | 商户类型的paypal email 收款账号


  
<h3 id="8.3">8.3 更新店铺设置中货币相关</h3>

* 接口地址 /api/v1/settings/update
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| area_id | int | 所在地区id | 否 
| area_en_name  |string| 所在地区的英文名称 | 否
| currency  | string| 货币简称 |  否 （设置店铺货币以上同时不能为空）
| currency_format  | string | 默认的货币展示格式 {{amount}}表示占位符合 会替换成实际的货币数值 | 否 （设置收款邮箱此项不能为空）  

请求body
```
{
	"area_id":3,
	"currency":"USD",
	"currency_format":"$ {{amount}} USD",
	"paypal_email":"729357-facilitator@qq.com"
}

```

* 接口返回 

`
```
{
    "status": true,
    "data": {
        "area_id": 3,
        "currency": "USD",
        "currency_format": "$ {{amount}} USD",
        "paypal_email": "729357-facilitator@qq.com"
    },
    "msg": "Saved successfully"
}
```

<h3 id="9.1">9.1 获取物流服务商列表</h3>

* 接口地址 /api/v1/freights/get_rate_quote
* 请求方法 GET
* 需要认证头


* 接口返回

```
{
    "status": true,
    "data": [
        {
            "product_id": 107,
            "product_name": "DHL",
            "basic_weight": 500,
            "basic_freight_rate": 11400,
            "additional_weight": 500,
            "additional_freight_rate": 3700,
            "currency": "CNY"
        },
        {
            "product_id": 108,
            "product_name": "EMS",
            "basic_weight": 500,
            "basic_freight_rate": 11600,
            "additional_weight": 500,
            "additional_freight_rate": 4000,
            "currency": "CNY"
        },
   ]
}        
```
* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| product_id | 选择的物流线路id
| product_name  | 线路名称
| basic_weight  | 首重 单位 g
| basic_freight_rate  | 首重费用 单位分
| additional_weight  | 续重 单位 g
| additional_freight_rate  | 续重费用 单位分
| currency  | 货币单位




<h3 id="9.2">9.2 批量新增或者更新运费</h3>

* 接口地址 /api/v1/freights
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| country_list | [] | 选择的国家id列表 | 是 
| shipping.product_id  | [] | 物流线路id | 是
| shipping.product_name  | []| 物流线路名称 |  是 
| shipping.basic_weight  | [] | 首重参数 | 非免邮必须
| shipping.basic_freight_rate  | [] | 首重费用 | 非免邮必须
| shipping.additional_weight  | [] | 续重 | 非免邮必须
| shipping.additional_weight_rate  | [] | 续重费用 | 非免邮必须
| free  | int | 免邮 | 免邮必须
  

请求body
```
{
	"country_list":[12],
	"shipping":{
		"product_id":[107,108],
		"product_name":["DHL","EMS"],
		"basic_weight":[600,600],
		"basic_freight_rate":[600,600],
		"additional_weight":[600,600],
		"additional_weight_rate":[600,600]
	},
	"free":1
}

```

* 接口返回 

`
```
{
    "status": true,
    "msg": "Added successfully"
}
```

<h3 id="9.3">9.3 按国家获取设置的运费方式</h3>

* 接口地址 /api/v1/freights/index
* 请求方法 GET
* 需要认证头


* 接口返回

```
{
    "status": true,
    "data": [
        {
            "country_name": "USA",
            "country_id": 3,
            "name_list": [
                "DHL",
                "EMS"
            ]
        },
        {
            "country_name": "USA",
            "country_id": 24999,
            "name_list": [
                "DHL",
                "EMS"
            ]
        }
    ]
}       
```
* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| country_id | 国家id
| country_name | 国家名称
| name_list | 已经选择的物流线路名称列表



<h3 id="9.4">9.4 获取单个国家设置的运费方式</h3>

* 接口地址 /api/v1/freights/{country_id}
* 请求方法 GET
* 需要认证头
* {country_id} 表示占占位符号 

* 接口返回

```
{
    "status": true,
    "data": [
        {
            "country_id": 3,
            "country_name": "USA",
            "product_id": 107,
            "product_name": "DHL",
            "basic_weight": 600,
            "basic_freight_rate": "600.00",
            "additional_weight": 600,
            "additional_weight_rate": "600.00"
        },
        {
            "country_id": 3,
            "country_name": "USA",
            "product_id": 108,
            "product_name": "EMS",
            "basic_weight": 600,
            "basic_freight_rate": "600.00",
            "additional_weight": 600,
            "additional_weight_rate": "600.00"
        }
    ]
}   
```
* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| product_id   | 物流线路id |
| product_name  | 物流线路名称 |  
| basic_weight  | 首重参数 |
| basic_freight_rate  | 首重费用 | 
| additional_weight  | 续重 | 
| additional_weight_rate  | 续重费用 | 



<h3 id="9.5">9.5 删除某些国家设置的运费</h3>

* 接口地址 /api/v1/freights/action
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| country_id_list  | 数组 |  需要操作的国家id列表  |是 
| type  | smallint |  3 删除（删除有风险） | 是

请求body
```
{
	"type":3,
	"country_id_list":[3]
}

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "successfully deleted" // msg 返回 在接口规则中有说明
 }
```


<h3 id="10.1">10.1 获取导航列表相关接口</h3>

* 接口地址 /api/v1/navigation/index
* 请求方法 GET
* 需要认证头


* 接口返回

```
{
    "status": true,
    "data": [
        {
            "navigation_id": 1,
            "navigation_title": "主菜单",
            "handle": "main-menu",
            "menu_list": [
                "关于我们",
                "联系我们"
            ]
        },
        {
            "navigation_id": 2,
            "navigation_title": "底部菜单",
            "handle": "footer",
            "menu_list": []
        }
    ]
}      
```
* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| navigation_id | 导航列表id
| navigation_title | 导航标题
| handle | 前端模板取值标志
| menu_list | 此栏目包含的菜单文字列表



<h3 id="10.2">10.2 获取导航中菜单列表</h3>

* 接口地址 /api/v1/menus/index
* 请求方法 GET
* 需要认证头


* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| navigation_id  | int |  获取的导航id  |是 

* 接口返回

```
{
    "status": true,
    "data": {
        "navigation_id": 1,
        "navigation_title": "主菜单",
        "handle": "main-menu",
        "menu_list": [
            {
                "id": 2,
                "title": "关于我们",
                "menu_type": "page",
                "subject_id": 1,
                "position": 1,
                "parent_menu_id": 0
            },
            {
                "id": 3,
                "title": "联系我们",
                "menu_type": "page",
                "subject_id": 2,
                "position": 2,
                "parent_menu_id": 0
            }
        ]
    }
}    
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| navigation_id | 导航列表id
| navigation_title | 导航标题
| handle | 前端模板取值标志
| menu_list.id | 菜单id
| menu_list.title | 菜单标题
| menu_list.menu_type | 菜单类型  home（首页） search（搜索） collection（集合） product（产品）page（单页）blog（博客）
| menu_list.subject_id | home与search 为空 其余表示相应类型的id （集合表示 集合相应的id product 表示相应的产品id page表示相应的单页id blog表示相应的博文分类id）  

<h3 id="10.3">10.3 获取菜单添加中类型列表</h3>

* 接口地址 /api/v1/menus/link_list
* 请求方法 GET
* 需要认证头



* 接口返回

```
{
    "status": true,
    "data": [
        {
            "title": "home",
            "menu_type": "home",
            "subclass": false
        },
        {
            "title": "search",
            "menu_type": "search",
            "subclass": false
        },
        {
            "title": "collection",
            "menu_type": "collection",
            "subclass": true,
            "menu_list": {
                "collection_id": -1,
                "title": "ALL Collections"
            }
        },
        {
            "title": "product",
            "menu_type": "product",
            "subclass": true,
            "menu_list": {
                "product_id": -1,
                "product_name": "All Product"
            }
        },
        {
            "title": "page",
            "menu_type": "page",
            "subclass": true
        },
        {
            "title": "blog",
            "menu_type": "blog",
            "subclass": true
        }
    ]
}    
```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| title | 类型显示名称
| menu_type | 菜单类型  home（首页） search（搜索） collection（集合） product（产品）page（单页） blog（博客）
| subclass | 是否含有子类   （collection与product 需要获得的数据 与上面的 menu_list合并）


<h3 id="10.4">10.4  新增菜单项</h3>

* 接口地址 /api/v1/menus
* 请求方法 POST
* 需要认证头


* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  菜单名称  | 是
| navigation_id  | int |  所属的导航id  | 是
| menu_type  | string |  home（首页） search（搜索） collection（集合） product（产品）page（单页） blog（博客分类）  | 是
| subject_id  | int |  目标id（home search 目标id为空 其他的传相应类型的id） | 否
| parent_menu_id  | int |  所属的父类id（0表示父分类） | 否
| position | int |  利于客户端的排序用 可以传适当的值 | 否


请求demo

```
{
	"title":"最新产品",
	"navigation_id":1,
	"menu_type":"collection",
	"subject_id":82,  // 82是集合的id
	"position":3,
	"parent_menu_id":0    
}

```



* 接口返回 

```
{
    "status": true,
    "data": {
        "menu_id": 4
    }
}
```


<h3 id="10.5">10.5 获取单个菜单详情</h3>

* 接口地址 /api/v1/menus/{id}
* 请求方法 GET
* 需要认证头
* {id} 表示占位符 表示需要请求的集合id 


* 接口返回

```
{
    "status": true,
    "data": {
        "menu_id": 4,
        "title": "最新产品",
        "menu_type": "collection",
        "subject_id": 82,
        "position": 3,
        "parent_menu_id": 0
    }
}

```

* 返回参数说明 

| 参数      |    说明
| :-------- | --------
| menu_id  | 菜单id
| title  | 菜单标题
| menu_type  | 类型
| subject_id  | 目标id
| position  | 排序id
| parent_menu_id  | 父类id

<h3 id="10.6">10.6  更新单个菜单详情</h3>

* 接口地址 /api/v1/menus/{id}
* 请求方法 POST
* 需要认证头
* {id} 占位符号

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| title  | string |  菜单名称  | 是
| navigation_id  | int |  所属的导航id  | 是
| menu_type  | string |  home（首页） search（搜索） collection（集合） product（产品）page（单页） blog（博客分类）  | 是
| subject_id  | int |  目标id（home search 目标id为空 其他的传相应类型的id） | 否
| parent_menu_id  | int |  所属的父类id（0表示父分类） | 否
| position | int |  利于客户端的排序用 可以传适当的值 | 否


请求demo

```
{
	"title":"最新产品",
	"navigation_id":1,
	"menu_type":"collection",
	"subject_id":82,  // 82是集合的id
	"position":3,
	"parent_menu_id":0    
}
```



* 接口返回 

```
{
    "status": true,
    "msg":"Update the collection is successful"
}
```

<h3 id="10.7">10.7 菜单批量操作</h3>

* 接口地址 /api/v1/menus/action
* 请求方法 POST
* 需要认证头
* 接口请求头 application/json

* 请求参数说明

| 参数      |    类型 | 说明  |  是否必须  |
| :-------- | --------:| :--: |:--: |
| menu_id_list  | 数组 |  需要操作的国家id列表  |是 
| type  | smallint |  3 删除（删除有风险） | 是

请求body
```
{
	"type":3,
	"menu_id_list":[4]
}

```

* 接口返回 

`
```
 {
     "status": true,
     "msg": "successfully deleted" // msg 返回 在接口规则中有说明
 }
```

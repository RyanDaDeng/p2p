这是一个类似localcoinswap/localbitcoin的p2p虚拟货币交易平台。

我想先做一个MVP，商家卖币，然后买家买币。

第一个界面你可以参考下./img.png该页面展示，买家从列表中选择商家的界面。商家的卖单展示虚拟货币的商品价格。

首先我需要设定整个网站的风格：
1. 网站需要支持light和dark两个模式
2. 网站风格使用tailwindcss来设定
3. 网站的设计语言可参考OKX的虚拟货币风格。
4. 网站需要手机和电脑端都适配
5. 网站的组件比如，dropdown，按钮，badge都可以设计成独立的组件然后复用。

我现在希望你先设计一个该页面，然后在后端写入一个entrypoint - Controllers/Web/AppRouteController.php,我已经写了一个例子。

然后前端是resources/js/Sell/Home.vue

请帮我先设计一个AppLayout - 这个layout有全局的loading，有顶部的nav。手机适配。sticky顶部nav。

然后使用这个applayout作为parent，然后设计这个sell的页面

目前所有前端数据先mock，不需要考虑后端api。


我现在需要设计一个订单和托管系统。订单创建的时候会记录vendor_id, client_id, seller_id, buyer_id,            
  ad_copy(用来快照当时的ad信息作为基础），status（trading，declined，completed，dispute,        
  expired)这些是基本属性，然后应该还有对应的托管步骤的系统，目前我设计的托管系统是这样的

seller，buyer
1. 客户 - 订单创建 - order_initiated
2. 商家确认 - vendor_confirmed
3. seller use 地址XXXXXX to send COIN in 托管
   3.1 确认按钮给seller - seller_paid
      3.1.1 确认完系统会开始检测是否到账 - escrow_received
4. buyer pays seller in AUD
   4.1 确认按钮给buyer - buyer_paid
5. seller 确认到账
   5.1 确认按钮给seller - seller_received
6. COIN放给buyer - escrow_released



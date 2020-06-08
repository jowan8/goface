30天通读NGINX源码

准备篇:
既然要通读,肯定要有源码,废话不多说,先去官网下载http://nginx.org/en/download.html
咦,不对啊,这跟平时看到的源码不一样啊...
重新打开官网,找到[Source Code]原来源码在这里,我们找到左边栏的zip/gz包,点击下载解压

首先我们要熟悉一下目录结构:
Nginx的源码主要分布在src/目录下，而src/目录下主要包含三部分比较重要的模块。

core：包含了Nginx的最基础的库和框架。包括了内存池、链表、hashmap、String等常用的数据结构。
event：事件模块。Nginx自己实现了事件模型。而我们所熟悉的Memcached是使用了Libevent的事件库。自己实现event会性能和效率方便更加高效。
http：实现HTTP的模块。实现了HTTP的具体协议的各种模块，该部分内容量比较大。
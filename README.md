## 项目描述

基于php原生代码实现linux的5大IO模型


## 基础配置

### 依赖安装

```shell
composer update
```

### 测试

```shell
php test/index.php
```

**如果有输出内容，则表示，框架正常。可以接着往下测试5大IO模型了**

## 目录和文件配置

```shell
---src
------Blocking
---------Worker.php

------Multiplexing
---------Worker.php

------Support
---------Helper.php

------WorkBase.php
------index.php



---test
------blocking
---------server.php
---------client.php

------multiplexing
---------server.php
---------client.php

------index.php
```



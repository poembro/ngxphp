# MVC 框架 

这是一个用PHP代码实现的MVC,也是在读过一个叫Lor框架后得到的思路；已经掌握各种开源框架(YII, CodeIgniter,laravel...)的你，或许还可以再玩一个"新货"。

- 特点一：为每一个请求绑定一个匿名函数(RESTful风格)；
- 特点二：为每个请求做路由，指定到控制器和方法(传统MVC框架风格)；
- 特点三：根据请求"按需"挂载对应的控制器和方法；


### 介绍

- 适合所有PHPer使用，因为它的简洁能让你轻松掌握整个框架是如何运作的；


### 案例一

- 最终它只有三步
- 1. 实例化一个对象；
- 2. 用这个对象往一个树上挂载节点；
- 3. 执行 (这里要传入参数，来告知框架执行具体的某个节点)；

```
    include FRAMEWORK_PATH .'Nig.php'; 
    $nig = \Nig\Nig::getInstance(APPLICATION_PATH . 'Config/Config.php');
     
    $nig->useNode('/', function($req, $res) {
        echo  9;
        return ;
    });
 
    $nig->run($_SERVER['REQUEST_URI']);
```
 
 
 
### 案例二
- 按需挂载
- 1. 实例化一个对象；
- 2. 按照请求参数，挂载对应的类和方法；
- 3. 执行 (这里要传入参数，来告知框架执行具体的某个节点)；

```
    include FRAMEWORK_PATH .'Nig.php'; 
    $nig = \Nig\Nig::getInstance(APPLICATION_PATH . 'Config/Config.php');
    
    $nig->autoNode($_SERVER['REQUEST_URI']);
    
    $nig->run($_SERVER['REQUEST_URI']);
```
 
 
 
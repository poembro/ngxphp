<html>
<head>
   <title>helloworld</title>
</head>
<body>
    <?php 
         foreach($list as $k => $v)
         {
    ?>

    <h1><?php echo $v['name']; ?></h1>
       
    <?php } ?>
    
    <nav class="pagination">
           <?php  include  dirname(__DIR__).'/mg/page.php';?>        
    </nav> 
</body>

</html>

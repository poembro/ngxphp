<style>
 .paging {
    padding: 10px;
    text-align: center;
}
 .paging a {
    border: 1px solid #eee;
    border-radius: 4px;
    display: inline-block;
    height: 28px;
    line-height: 28px;
    margin: 0 5px;
    padding: 0 10px;
    vertical-align: top;
}
.paging a.curr {
    background-color: #666;
    border-color: #555;
    color: #fff;
}
</style> 
<?php  if(isset($page)) {  ?>
       <div class="paging">
           <a href="<?php echo $page['home'];?>">首页</a>
           <a href="<?php if($page['prev']) { echo $page['prev'];}else{ echo 'javascript:;';} ?>"><上一页</a>
           <?php  
            foreach ($page['num'] as $key => $value) 
            {
                echo '<a href="' . $value . '" ';
                if ($key == $page['page'])
                { 
                     echo  'class="curr"';
                }
                echo ' >'.$key .'</a>';  
            }
           ?>
           
           <?php
            echo '<a href="';
                 if ($page['next'])
                 {
                     echo $page['next'];
                 }
                 else
                 {
                     echo 'javascript:;';
                 }
            echo '">下一页 ></a>'; 
            
            echo '<a href="'.$page['end'].'">末页</a>';
            ?>
      </div>
          
<?php  } ?> 
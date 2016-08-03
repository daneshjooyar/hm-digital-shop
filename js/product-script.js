jQuery(document).ready(function($){
   $("a.hmdp_buy_show").click(function(){
       $(this).next().slideDown(2000).end().hide(1000);
       return false;
   }); 
});




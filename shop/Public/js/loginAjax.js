jQuery(docunment).ready(function(){
    
    
    //制作json传送数据
    var jsondata={'name':$('#user').val(),'password':$('#password').val()};

    $('#submit').click(funcrion(){
         // $.post('url',{json:数据},{回调函数});
         var postUrl = 
         $.post(postUrl,jsondata,function(data){
               //接受返回数据
               if(data.rs == 1){
                   
               }else{
                   
               }
          }
    });
});



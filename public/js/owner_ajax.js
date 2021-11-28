// チェックボックス
    
$(function(){
    for (let s_id = 2; s_id <= 1000; s_id++) {
        $btn = $(".btn_"+s_id); // イベント発火の条件にしたいクラス
        $btn.on("click", function(e){ // イベント発火
            e.stopPropagation(); // 通常処理ストップ
            var $this = $(this); //　下の変更したいクラスを$thisに入れる
            let id = $(".btn_"+s_id).val(); // valueの値を代入
            //　Ajaxの設定
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {"id" : id },
                dataType: "json"

            }).done(function(data){
                // チェック取り消しのスタイル
                $this.children('i').toggleClass('far');
                // チェック押した時のスタイル
                $this.children('i').toggleClass('fas');
                $this.children('i').toggleClass('active');
            
            }).fail(function(XMLHttpRequest, status, e){
                alert('失敗');
            });
        });
        
    }
    
});
    



// リセット
$(function(){
    $reset = $(".reset");
    $reset.on("click", function(e){
        e.stopPropagation();
        var $this = $(".re");
        let reset = $(".reset").val();
        $.ajax({
            type:"POST",
            url:"ajax.php",
            data: {"reset" : reset },
            dataType: "json"

        }).done(function(data){
            alert('リセットしました。');
                
                // チェック取り消しのスタイル
                $this.children('i').addClass('far');
                // チェック押した時のスタイル
                $this.children('i').removeClass('fas');
                $this.children('i').removeClass('active');
            
                // $this.children('i').removeClass('active');
        }).fail(function(XMLHttpRequest, status, e){
            alert('失敗');
        });
    });
});

// オーナー在庫更新
$(function(){
    for (let x = 1; x <= 1000; x++) {
        $stock = $(".stock_"+x);

        $stock.on("click", function(e){
            e.stopPropagation(); // 通常処理ストップ
            var $this = $(this); //　下の変更したいクラスを$thisに入れる
            let id = $(".stock_"+x).val(); // valueの値を代入
            let value = $(".count_"+x).val();
            //　Ajaxの設定
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    "s_id" : id,
                    "stock_count" : value
                },
                dataType: "json"

            }).done(function(data){
                console.log(data);
                $('.stock_count'+x).html(data);
            
            }).fail(function(XMLHttpRequest, status, e){
                alert('失敗');
            });
        });
        
    }   
});

// 商品削除
$(function(){
    for (let x = 1; x <= 1000; x++) {
        $delete = $(".delete_"+x);
        
        $delete.on("click", function(e){
            let check = confirm("削除してもよろしいですか？")
            if(check == true){
                e.stopPropagation();
                var $this = $(this);
                let id = $(".delete_"+x).val();
                
                $.ajax({
                    type:"POST",
                    url:"ajax.php",
                    dataType:"json",
                    data:{"delete" : id}

                }).done(function(data){
                    $this.parent().parent().remove();

                }).fail(function(XMLHttpRequest, status, e){
                    alert('失敗');
                });
            }    
        });    
    }
});

// 商品編集
$(function(){
    $update = $(".update");
    $update.on("click", function(e){
        let id = $(".update").val();
        let name = $(".name").val();
        let class_id = $(".class").val();
        
        $.ajax({
            type:"POST",
            url:"ajax.php",
            dataType:"json",
            data:{
                "stock_id" : id,
                "stock_name" : name,
                "stock_class" : class_id
            }
        }).done(function(data){
            $('.change_name').html(data.name);
            if(data.class == 1){
                var class_name = 'コーヒー豆';
            }
            if(data.class == 2){
                var class_name = 'ミルク';
            }
            if(data.class == 3){
                var class_name = '資材';
            }
            if(data.class == 4){
                var class_name = '食品';
            }
            $('.change_class').html(class_name);

        }).fail(function(XMLHttpRequest, status, e){
            alert('失敗');
        });
    });
});

// // 適正入力
// $(function(){
//     $send = $("#send");
//     $send.on("click", function(e){
//         e.stopPropagation();

//         $.ajax({
//             type:"POST",
//             url:"ajax.php",
//             dataType:"json",
//             data:{
//                 // コーヒー豆
//                 "Appropriate1_0":$(".Appropriate1_0").val(),
//                 "Appropriate1_1":$(".Appropriate1_1").val(),
//                 "Appropriate1_2":$(".Appropriate1_2").val(),
//                 "Appropriate1_3":$(".Appropriate1_3").val(),
//                 "Appropriate1_4":$(".Appropriate1_4").val(),
//                 "Appropriate1_5":$(".Appropriate1_5").val(),
//                 "Appropriate1_6":$(".Appropriate1_6").val(),
//                 // 牛乳
//                 "Appropriate2_0":$(".Appropriate2_0").val(),
//                 "Appropriate2_1":$(".Appropriate2_1").val(),
//                 "Appropriate2_2":$(".Appropriate2_2").val(),
//                 "Appropriate2_3":$(".Appropriate2_3").val(),
//                 "Appropriate2_4":$(".Appropriate2_4").val(),
//                 "Appropriate2_5":$(".Appropriate2_5").val(),
//                 "Appropriate2_6":$(".Appropriate2_6").val(),
//                 // 豆乳
//                 "Appropriate3_0":$(".Appropriate3_0").val(),
//                 "Appropriate3_1":$(".Appropriate3_1").val(),
//                 "Appropriate3_2":$(".Appropriate3_2").val(),
//                 "Appropriate3_3":$(".Appropriate3_3").val(),
//                 "Appropriate3_4":$(".Appropriate3_4").val(),
//                 "Appropriate3_5":$(".Appropriate3_5").val(),
//                 "Appropriate3_6":$(".Appropriate3_6").val(),
//                 // Sサイズカップ
//                 "Appropriate4_0":$(".Appropriate4_0").val(),
//                 "Appropriate4_1":$(".Appropriate4_1").val(),
//                 "Appropriate4_2":$(".Appropriate4_2").val(),
//                 "Appropriate4_3":$(".Appropriate4_3").val(),
//                 "Appropriate4_4":$(".Appropriate4_4").val(),
//                 "Appropriate4_5":$(".Appropriate4_5").val(),
//                 "Appropriate4_6":$(".Appropriate4_6").val(),
//                 // Mサイズカップ
//                 "Appropriate5_0":$(".Appropriate5_0").val(),
//                 "Appropriate5_1":$(".Appropriate5_1").val(),
//                 "Appropriate5_2":$(".Appropriate5_2").val(),
//                 "Appropriate5_3":$(".Appropriate5_3").val(),
//                 "Appropriate5_4":$(".Appropriate5_4").val(),
//                 "Appropriate5_5":$(".Appropriate5_5").val(),
//                 "Appropriate5_6":$(".Appropriate5_6").val(),
//                 // Lサイズカップ
//                 "Appropriate6_0":$(".Appropriate6_0").val(),
//                 "Appropriate6_1":$(".Appropriate6_1").val(),
//                 "Appropriate6_2":$(".Appropriate6_2").val(),
//                 "Appropriate6_3":$(".Appropriate6_3").val(),
//                 "Appropriate6_4":$(".Appropriate6_4").val(),
//                 "Appropriate6_5":$(".Appropriate6_5").val(),
//                 "Appropriate6_6":$(".Appropriate6_6").val(),
//                 // ポーションシロップ
//                 "Appropriate7_0":$(".Appropriate7_0").val(),
//                 "Appropriate7_1":$(".Appropriate7_1").val(),
//                 "Appropriate7_2":$(".Appropriate7_2").val(),
//                 "Appropriate7_3":$(".Appropriate7_3").val(),
//                 "Appropriate7_4":$(".Appropriate7_4").val(),
//                 "Appropriate7_5":$(".Appropriate7_5").val(),
//                 "Appropriate7_6":$(".Appropriate7_6").val(),
//                 // ポーションミルク
//                 "Appropriate8_0":$(".Appropriate8_0").val(),
//                 "Appropriate8_1":$(".Appropriate8_1").val(),
//                 "Appropriate8_2":$(".Appropriate8_2").val(),
//                 "Appropriate8_3":$(".Appropriate8_3").val(),
//                 "Appropriate8_4":$(".Appropriate8_4").val(),
//                 "Appropriate8_5":$(".Appropriate8_5").val(),
//                 "Appropriate8_6":$(".Appropriate8_6").val(),
//                 // パン
//                 "Appropriate9_0":$(".Appropriate9_0").val(),
//                 "Appropriate9_1":$(".Appropriate9_1").val(),
//                 "Appropriate9_2":$(".Appropriate9_2").val(),
//                 "Appropriate9_3":$(".Appropriate9_3").val(),
//                 "Appropriate9_4":$(".Appropriate9_4").val(),
//                 "Appropriate9_5":$(".Appropriate9_5").val(),
//                 "Appropriate9_6":$(".Appropriate9_6").val(),
//                 // ばなな
//                 "Appropriate10_0":$(".Appropriate10_0").val(),
//                 "Appropriate10_1":$(".Appropriate10_1").val(),
//                 "Appropriate10_2":$(".Appropriate10_2").val(),
//                 "Appropriate10_3":$(".Appropriate10_3").val(),
//                 "Appropriate10_4":$(".Appropriate10_4").val(),
//                 "Appropriate10_5":$(".Appropriate10_5").val(),
//                 "Appropriate10_6":$(".Appropriate10_6").val(),

//             }
//         }).done(function(data){
//             let Appropriates 
//         }).fail(function(XMLHttpRequest, status, e){
//             alert('失敗');
//         });
//     });
// });
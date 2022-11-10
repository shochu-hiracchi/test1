/*
■必殺扇メモ

-------------変数や配列は下記をコピーして書き換え-------------

const ary = ["こすげ", "たつや"];

-------------イベントは下記をコピーして必要なところを書き換える！！！！-------------

$('#start').on("click", function(){
});

-------------ajaxは以下をコピーして書き換える！！！！-------------
$.getJSON('', function(data){


});

*/

// const message = new SpeechSynthesisUtterance();
// message.lang = 'en-US'; // 言語設定

// $("#btn").on("click", function(){
// 	message.text = "I have a pen";// しゃべる言葉を設定
//   speechSynthesis.speak(message); // 実際にしゃべる
// });




window.onload = function () {
    $(function($){
        $("#hamburger").click(function () {
            $(this).toggleClass('active');
        });

        var proCat = $('category').val();
        var proName = $('proName').val();
        var sortby = $('sortby').val();
        proDisplay(proCat,proName,sortby);

        $(document).on('change','.search-sort',function(){
            $('#proList').empty();
            let thisVal = $(this).val();
            str_var($(this).attr('id'),thisVal);
            proDisplay(proCat,proName,sortby);
        });

        $('#').fadeIn()

        function str_var(arg_searchId , arg_val){
            if(arg_searchId === 'proCat'){
                proCat = arg_val;
            } else if(arg_searchId === 'proName') {
                proName = arg_val;
            } else if(arg_searchId === 'sortby') {
                sortby = arg_val;
            }
        }


        function proDisplay(arg_proCat,arg_proName,arg_sortby) {
            $.ajax({
                type: 'POST',
                url: 'ajax/ajax_proList.php',
                data: {
                    JS_proCat : arg_proCat,
                    JS_proName : arg_proName,
                    JS_sort : arg_sortby
                }
            }).done(function (data) {
                let obj = JSON.parse(data);
                console.log(obj);
                let add_tmp = '<div class="proList_block">';
                let returnCnt = obj.length;
                let num = 0;
                for (let i = 1; i <= returnCnt; i++) {
                    add_tmp +=
                    '<div class="proList_element animation-fadeIn">' +
                        '<img src="images/' + obj[num]['proImage'] + '" class="proList_element_image">' +
                        '<div class="stockCnt"><span>' + obj[num]['stock'] + '</span></div>' +
                        '<img src="images/pin.png" class="proList_element_clip">' +
                        '<div class="proList_element_proName">' + obj[num]['proName'] + '</div>' +
                    '</div>';

                    // 3個のelemを追加したら閉じて、まだありそうならblockを追加する。
                    if(i % 3 === 0){
                        add_tmp += '</div>';
                        if(i <= returnCnt){
                            add_tmp += '<div class="proList_block">';
                        }
                    }
                    // elemの追加が終わったら、addを追加。blockが余るならblankを追加
                    if(i === returnCnt){
                        console.log('全て表示済み');
                        add_tmp +=
                            '<div class="proList_element add_product">' +
                                '<div><img src="images/plus.svg"></div>' +
                            '</div>';
                        if(i % 3 === 0){
                            console.log('blankが２こ必要');
                            add_tmp +=
                                '<div class="proList_element blanc">' +
                                '</div>' +
                                '<div class="proList_element blanc">' +
                                '</div>';
                        } else if((i - 1) % 3 === 0){
                            console.log('blankが１こ必要');
                            add_tmp +=
                                '<div class="proList_element blanc">' +
                                '</div>';
                            
                        }
                        // 最後にblockを閉じる
                        add_tmp += '</div>';
                    }
                    num++;
                };
                $('#proList').append(add_tmp);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }).always(function (){
            });
        }
    });
}
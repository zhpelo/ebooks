function printDOM(){
    var bodyHtml = window.document.body.innerHTML;
    var printStart = '<print-contents>';
    var printEnd = '</print-contents>';
    var printHtmlStart = bodyHtml.slice(bodyHtml.indexOf(printStart));
    var printHtml = printHtmlStart.slice(0,printHtmlStart.indexOf(printEnd));
    window.document.body.innerHTML = printHtml;
    window.print();
    window.document.body.innerHTML = bodyHtml;
}

$(document).on("keyup",function(e){
    var m_e = e || window.event;
    var keycode = m_e.which;
    if(keycode === 37){
        if($("#prev-chapter").attr('href')){
            window.location.href = $("#prev-chapter").attr('href');
        }
    }else if(keycode === 39){
        if($("#next-chapter").attr('href')){
            window.location.href = $("#next-chapter").attr('href');
        }
       
    }
});
$(document).on('click','#add_ebook_btn',function(){
    window.location.href = "https://user.7sbook.com/";
});

$(document).on('click','#buy_book',function(){
    alert("暂未和任何书店合作！");
});



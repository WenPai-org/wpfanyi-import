(function ($) {
  $(".layui-tab-title li").click(function () {
    var picTabNum = $(this).index();
    sessionStorage.setItem("picTabNum", picTabNum);
    var getPicTabNum = sessionStorage.getItem("picTabNum");
    $(".layui-tab-title li").eq(getPicTabNum).addClass("layui-this").siblings().removeClass("layui-this");
    $(".layui-tab-content>div").eq(getPicTabNum).addClass("layui-show").siblings().removeClass("layui-show");
  });
  $(function () {
    var getPicTabNum = sessionStorage.getItem("picTabNum");
    $(".layui-tab-title li").eq(getPicTabNum).addClass("layui-this").siblings().removeClass("layui-this");
    $(".layui-tab-content>div").eq(getPicTabNum).addClass("layui-show").siblings().removeClass("layui-show");
  })

  $("form").sisyphus();
  $("input").focus(function () {
    $(this).parent().children(".input_clear").show();
  });
  $("input").blur(function () {
    if ($(this).val() == '') {
      $(this).parent().children(".input_clear").hide();
    }
  });
  $(".input_clear").click(function () {
    $(this).parent().find('input').val('');
    $(this).hide();
  });

  layui.use('element', function () {
    var element = layui.element;

  });
})(jQuery);
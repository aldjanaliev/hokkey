function ready(callback){
    if (document.readyState!='loading') callback();
    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', callback);
    else document.attachEvent('onreadystatechange', function(){
        if (document.readyState=='complete') callback();
    });
}
jql = {}

var wndw = {
    now_step: 1,
    window_now: $(),
    window_next: $(),
    main: $(".windows"),
    end: $(".window[data-step='end']"),
    next: function(){
        var step_next = wndw.now_step + 1,
            have_next = $(".window[data-step='"+step_next+"']").length

        wndw.window_now = $(".window[data-step='"+wndw.now_step+"']")
        wndw.window_next = $(".window[data-step='"+step_next+"']")
        $(".step[data-step='"+(step_next-1)+"']").addClass("active")

        if(have_next){
            wndw.main.attr("data-step", step_next)
            jql.test.attr("data-step", step_next)
            $(".count-have span").text(step_next)

            wndw.now_step = step_next

            wndw.window_now.addClass("window-prev")
            setTimeout(function(){
                wndw.window_next.addClass("active")
                wndw.set_height()
                wndw.check_data()
            }, 50)

            yMetrika(step_next+'_question')
        }else{
            yMetrika('contacts')

            wndw.main.attr("data-step", "end")
            jql.test.attr("data-step", "end")
            wndw.window_now.addClass("window-prev")
            setTimeout(function(){
                $("body").addClass("end-test")
            }, 300)
            setTimeout(function(){
                $(".chekboxs label").last().click()

                $(".window[data-step='end']").addClass("active")
                wndw.set_height()

                $(document.getElementById("circle")).removeAttr("data-load").find("> *:not(.good-icon)").remove()
                var bar = new ProgressBar.Circle(document.getElementById("circle"), {
                    color: color_items ? color_items : '#1e92fd',
                    strokeWidth: 11,
                    trailWidth: 11,
                    easing: 'easeInOut',
                    linecap: 'round',
                    duration: 6000,
                    text: {
                        autoStyleContainer: false
                    },
                    from: { color: color_items ? color_items : '#1e92fd', width: 11 },
                    to: { color: color_items ? color_items : '#1e92fd', width: 11 },
                    step: function(state, circle) {
                        circle.path.setAttribute('stroke', state.color);
                        circle.path.setAttribute('stroke-width', state.width);

                        var value = Math.round(circle.value() * 100);
                        circle.setText(value);
                        if(value == 100) {
                            setTimeout(function(){
                                document.getElementById("circle").setAttribute('data-load', 100)
                                setTimeout(function(){
                                    $(".window[data-step='end']").addClass('loaded')
                                }, 200)
                            }, 300)
                        }
                    }
                })
                bar.animate(1.0)
            }, 50)
        }

    },
    prev: function(){
        var step_next = wndw.now_step - 1

        wndw.window_now = $(".window[data-step='"+wndw.now_step+"']")
        wndw.window_next = $(".window[data-step='"+step_next+"']")
        $(".step[data-step='"+(wndw.now_step - 1)+"']").removeClass("active")
        $(".count-have span").text(step_next)

        wndw.main.attr("data-step", step_next)
        jql.test.attr("data-step", step_next)

        wndw.now_step = step_next

        wndw.window_now.removeClass("active")
        setTimeout(function(){
            wndw.window_next.removeClass("window-prev")
            wndw.set_height()
        }, 50)

        // selected
        wndw.window_now.removeClass("selected can-next")
        wndw.window_next.removeClass("selected can-next")
        wndw.window_now.find("input[type='radio'], input[type='checkbox']").prop("checked", false)
        wndw.window_next.find("input[type='radio'], input[type='checkbox']").prop("checked", false)
        wndw.window_now.find("input[type='text']").val("")
        wndw.window_next.find("input[type='text']").val("")

        wndw.check_data()
        wndw.check_active()
    },
    set_height: function(){
        var height = $(".window.active").last().height()
        wndw.main.height(height)

        check_bottom("set_height")
        setTimeout(function(){
            check_bottom("set_height")
        }, 400)

    },
    check_active: function(){
        var now_wndw = $(".window[data-step='"+wndw.now_step+"']"),
            inputs = now_wndw.find(".input > input"),
            can = false
        for (var i = 0; i < inputs.length; i++) {
            var input = $(inputs[i]),
                type = input.attr("type"),
                value = input.val().trim()

            if(type == "text" || type == "number"){
                if(value) can = true
            } else if(value == "checkbox-text"){
                if(input.prop('checked') && input.parents(".input").find("textarea").val()){
                    can = true
                }
            } else if(input.prop('checked')){
                can = true
            }
        }
        if(can){
            now_wndw.addClass("can-next")
            jql.test.addClass("can-next")
        }else{
            now_wndw.removeClass("can-next")
            jql.test.removeClass("can-next")
        }
    },
    check_data: function(){
        var item_second = $(".test-data-second"),
            data_second = $(".window.active").last().find(".step-blk")
        if(data_second.length){
            item_second.addClass("have-data-check")
        }else{
            item_second.removeClass("have-data-check")
        }
        $(".step-data-left").html($(".window.active").last().find(".window-rg").html().replace("step_", "step_mob_"))
    },
    go_first: function(){
        $(".window:not([data-step='1'])").removeClass("active selected window-prev")
        $(".step:not([data-step='1'])").removeClass('active')
        $("body").removeClass("end-test")
        wndw.now_step = 2
        wndw.prev()
    },
    go_end: function(){
        $(".window:not([data-step='end'])").addClass("active selected window-prev")
        $(".step:not(:last-child)").addClass('active')
        wndw.now_step = $(".step").length
        wndw.next()
    }
}
var initPhotoSwipeFromDOM = function(gallery_item) {
    var items = [],
        gallery,
        options,
        index,
        items_list = $(":not(.swiper-slide-duplicate) [data-photo='"+gallery_item.attr("data-photo")+"']")

    for (var i = 0; i < items_list.length; i++) {
        var size = items_list[i].getAttribute('data-size').split('x'),
            title = items_list[i].getAttribute('alt')

        item = {
            src: items_list[i].getAttribute('href'),
            w: parseInt(size[0], 10),
            h: parseInt(size[1], 10),
            msrc: items_list[i].children[0].getAttribute('src'),
            el: items_list[i],
            title: title ? title : ""
        }
        items.push(item)
        if(parseInt($(items_list[i]).parent().attr("data-i")) == parseInt(gallery_item.parent().attr("data-i"))) index = i
    }

    options = {
        index: index,
        history: false,
        shareEl: false,
        galleryUID: 0,
        closeOnScroll : false,
        pinchToClose: false,

        clickToCloseNonZoomable: false,
        tapToClose: false,
        loop: true,
    }
    pswp = new PhotoSwipe(document.querySelectorAll('.pswp')[0], PhotoSwipeUI_Default, items, options)
    pswp.init()
}
var jql = {}
ready(function(){

    wndw.main = $(".windows")
    wndw.set_height()
    wndw.check_data()
    window.is_mobile = false

    $(document).on("click", "[data-metr]", function(){
        var key = $(this).attr("data-metr")
        yMetrika(key)
    })

    // mobile header
    $(".mobile-btn").click(function(){ jql.body.addClass("header-open") })
    $(".logo-btn-mobile").click(function(){ jql.body.removeClass("header-open") })
    // test mobile info
    $(".test-btns-left").click(function(){ jql.body.addClass("step-info-open"); $(".have-data-check").removeClass("have-data-check") })
    $(".step-data-btn, .step-data-background").click(function(){ jql.body.removeClass("step-info-open") })


    // main slider
    var slider_main = $(".swiper-main")
    if(slider_main.first().find(" .swiper-slide").length > 1){
        var main_swiper = new Swiper('.swiper-main .swiper-container', {
            effect: 'fade',
            loop: true,
            speed: 400,
            autoplay: {
                delay: 3600,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        })
    }else{
        slider_main.addClass("swiper-main-one")
    }
    // open
    jql.bdy = document.querySelector("body")
    jql.test = $("#test")
    jql.interactive = $("#interactive")
    jql.body = $("body")

    // main photo swiper
    var openPhotoSwipe = function() {
        var pswpElement = document.querySelectorAll('.pswp')[0];
        var items = JSON.parse(vizualizacya)
        var options = {
            history: false,
            focus: false,
            showAnimationDuration: 0,
            hideAnimationDuration: 0
        };

        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items);
        gallery.init();
    }
    var open_viz = document.querySelectorAll('[data-viz]')
    for (var i = 0; i < open_viz.length; i++) {
        open_viz[i].addEventListener("click", openPhotoSwipe);
    }

    // slider
    sldr('#main-slider .swiper-container, .sldr .swiper-container')

	$(document).on('click', '[data-photo]', function(){
		initPhotoSwipeFromDOM($(this))
		return false;
	})


    $(document).on("click", ".akp-text-have", function(e){
        var item = $(this),
            content = item.find(".akp-text-content")
        if (!$(e.target).is("a")) {
            if(item.hasClass("akp-open")){
                item.removeClass("akp-open")
                content.slideUp(220)
            }else{
                var active_item = $(".akp-text-have.akp-open")
                if(active_item.length) active_item.click()

                item.addClass("akp-open")
                content.slideDown(220)
            }
        }
    })
    $(document).click(function (e){
        var div = $(".akp-text-have")
        if (!div.is(e.target) && div.has(e.target).length === 0){
            $(".akp-text-have.akp-open").click()
        }
    })


	$(document).on('click', '.input-content-right', function(){
        var item = $(this).parents(".input"),
            content = item.find(".input-content")
        if(item.hasClass("open")){
            item.removeClass('open')
            content.slideUp(220)
        }else{
            $(".input.open .input-content-right").click() 
            item.addClass('open')
            content.slideDown(220)
        }
        return false
    })
    $(document).click(function (e){
        var div = $(".input-content-right")
        if (!div.is(e.target) && div.has(e.target).length === 0){
            $(".input.open .input-content-right").click()
        }
    });
    /* TEST */
	$(document).on('change', '.input:not(.input-default) > input', function(){
		var input = $(this),
            parent = input.parent()
            value = input.val(),
            checked = input.prop("checked"),
            type = input.attr("type"),
            name = input.attr("name"),
            wndwr = input.parents(".window")

        if(((type == "radio" && parent.hasClass("radio")) || type == "checkbox" && parent.hasClass("checkbox")) && checked){
            wndwr.addClass("selected")

            if(type == "radio") wndwr.find(".next-step").click()

            var sib = parent.siblings()
            sib.find("input[type='text']").val("")
            if(type == "radio") {
                sib.find("input:not([type='text'])").prop("checked", false)
            }else{
                sib.find("input[type='text']").val("")
                sib.find("input[value='checkbox-text']").prop("checked", false)

            }

        }else if(type == "radio" && parent.hasClass("checkbox-text")){
            console.log(checked)
            if(checked){
                wndwr.addClass("selected")

                var sib = parent.siblings()
                sib.find("input[type='text']").val("")
                sib.find("input:not([type='text'])").prop("checked", false)
            }else{

            }
        }

        wndw.check_active()
	})
    $('.input-default input').keyup(function() {
        wndw.check_active()
    })
	$(document).on('change keyup', '.checkbox-text textarea', function(){
		var textarea = $(this),
            parent = textarea.parents(".input")
            value = textarea.val().trim(),
            wndwr = textarea.parents(".window")

        if(value){
            parent.find("> input").prop("checked", true)
            wndwr.find("input:not([type='text']):not([value='checkbox-text'])").prop("checked", false)

            parent.addClass("checkbox-text-have")
        }else{
            parent.removeClass("checkbox-text-have")
        }
        wndw.check_active()
	})


    $("[name='phone']").each(function(){
        var im = new Inputmask("9(999)999-99-99")

        im.mask(this)
    })
    $(document).on("change keyup", "[name='phone']", function(){
        var input = $(this),
            val = input.val().replace(/\D+/g,""),
            gg = val.length == 11,
            parents = input.parents(".inputs-bottom"),
            form = input.parents("form")
        if(!form.length) form = input.parents(".text-blk")

        gg ? parents.addClass("can-send") : parents.removeClass("can-send")
        gg ? form.addClass("can-send") : form.removeClass("can-send")
    })

    $(document).on("click", ".btn-send-test", function(){
        var form = $(this).parents("form")
        form.ajaxSubmit({
			dataType: 'json'
		})

        // phone
        var phone = $(".window[data-step='end'] input[name='phone']").val()
        $(".ajax [name='phone']").val(phone).change()

        form.find("input[type='radio'], input[type='checkbox']").prop("checked", false)
        form.find("textarea, input[type='text']").val("")

        setTimeout(function(){
            var body = $("body")
            body.removeClass("close").addClass("open")

            document.querySelector("#test").scrollIntoView({
                behavior: 'smooth'
            })

            setTimeout(function(){
                $("body").addClass("open-end")
                sldr('#page .swiper-container')
            }, 200)
        }, 200)

        yMetrika('result')

        return false
    })
    $(document).on("click", "form.ajax [type='submit'], .btn-ajax", function(){
        var form = $(this).parents("form"),
            action = form.find("input[name='action']").val()
        form.ajaxSubmit({
			dataType: 'json'
		})

        if(action=="updatePhone"){
            var phone = form.find("input[name='phone']").val()
            $("[name='phone']").val(phone)
        }

        form.find("input[type='radio'], input[type='checkbox']").prop("checked", false)
        form.find("textarea, input[type='text']").val("")
        form.find(".select-file").removeClass("select-file")
        var file_text = form.find(".file-text")
        file_text.html(file_text.attr("data-clear"))

        var open_modal = $(".modal.is-open")
        if(open_modal.length) MicroModal.close(open_modal.attr("id").replace("#", ""));


        MicroModal.show(action=="updatePhone" ? 'thanks-number' : 'thanks');
        return false
    })



	$(document).on('change', '[name="call"]', function(){
        var input = $(this),
            checked = input.prop("checked"),
            val = input.val(),
            windw = $(".window[data-step='end']"),
            windw_main = $(".windows")

        if(checked){
            if(val.indexOf("email") != -1){
                $(".input-phone").slideDown({
                    duration: 200,
                    progress: function(){
                        wndw.main.height(wndw.end.height())
                    }
                })
            }else{
                $(".input-phone").slideUp({
                    duration: 200,
                    progress: function(){
                        wndw.main.height(wndw.end.height())
                    }
                })
            }
        }
    })

    $(".window-content .info").click(function(e) {
        return false;
    });


    $('body').on('change', 'input[type="file"]', function (e) {
    	var file_name = this.value.replace(/\\/g, '/').replace(/.*\//, ''),
            reader = new FileReader()
        parent_file_load = $(this).parents('.form-file')
        if(file_name){
            parent_file_load.addClass("select-file")
        	parent_file_load.find('.file-text').text(file_name)
        }
    })

    MicroModal.init({
      openTrigger: 'data-micromodal-open', // [3]
      closeTrigger: 'data-micromodal-close', // [4]
      openClass: 'is-open', // [5]
      disableScroll: false, // [6]
      awaitOpenAnimation: false, // [8]
      awaitCloseAnimation: true, // [9]
    });






    /* VIDEO */
    $(document).on("click", ".video-close", function(){
        $(".video-open").removeClass("video-open")
        $("#video")[0].muted = true
    })
    $(document).on("click", ".video-close-always", function(){
        $(".video").remove()
    })
    $(document).on("click", ".video", function(e){
        if($(e.target).is(".video-close") || $(e.target).is(".video-close-always")) return false
        var vd = $(this)
        if(!vd.hasClass("video-open")){
            vd.addClass("video-open")
            $("#video")[0].muted = false
        }
    })
    try {
        window.parent.good_load()
    } catch (e) {}

})
// RESIZE
window.main_width = 0
window.main_height = 0
function resize_action(){
    console.log("resize")
    window.width_now = $(window).width()
	window.height_now = $(window).height()
	if (window.width_now == window.main_width && window.height_now == window.main_height) return;

    window.width_action = window.width_now != window.main_width
    window.main_width = window.width_now
	window.main_height = window.height_now

    // mobile or not
    if(!jql.body) return false

    for (var key in  window.sizes) {
        if (window.sizes.hasOwnProperty(key)) {
            var szs = window.sizes[key],
                dt = {}
            for (var i = 0; i < szs.length; i++) {
                var sz = szs[i]
                if(sz.size == "main" && !dt.size) dt = sz
                if(parseInt(sz.size) > 0 && window.main_width <= parseInt(sz.size)) {
                    dt = sz
                }
            }
            if(dt.value){
                $("[data-size='"+key+"']").html(dt.value)
            }
        }
    }
}
window.sizes = JSON.parse(sizes);
$(window).resize(function(){ resize_action() })
window.addEventListener("orientationchange", function() { resize_action() });
$(window).ready(function(){ resize_action() })


function sldr(selector){
    new Swiper(selector, {
        effect: 'fade',
        loop: true,
        speed: 400,
        // autoplay: {
        //     delay: 4000,
        //     disableOnInteraction: false,
        // },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        on: {
            init: function (swiper) {
                var hash = swiper.el.getAttribute("data-hash")
                window[hash] = swiper
                var items = document.querySelectorAll(".swiper-itme[data-hash='"+hash+"']")
                for (var i = 0; i < items.length; i++) {
                    items[i].addEventListener("click", function(){
                        var item = this,
                            i = item.getAttribute("data-i"),
                            hash = item.getAttribute("data-hash")
                        window[hash].slideTo(i)
                    })
                }
            },
            slideChange: function (swiper) {
                var main = $(swiper.el),
                    items = main.parents(".slider-wrapper").find(".swiper-itme"),
                    i = geSlideDataIndex(swiper),
                    item = $(items[i])

                item.addClass('active')
                item.siblings().removeClass('active')

                var name = item.attr("data-name"),
                    city = item.attr("data-city"),
                    blue = item.attr("data-blue"),
                    tender = item.attr("data-tender"),
                    html = "",
                    parnts = main.parents(".slider-body"),
                    textbody = parnts.find(".slider-text")
                if(blue) html += "<div class='sld-blue'><span class='color'>"+blue+"</span></div>"
                if(name) html += "<div class='sld-name'>"+name+"</div>"
                if(city) html += "<div class='sld-city'>"+city+"</div>"
                if(tender) html += "<div class='sld-tender'>"+tender+"</div>"
                textbody.html(html)
                html ? parnts.addClass("have-text") : parnts.removeClass("have-text")
            },
        },
    })
}
function sendForm(){
    const request = new XMLHttpRequest();
    const url = "send.php";
    request.open("POST", url, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.addEventListener("readystatechange", () => {
        if(request.readyState === 4 && request.status === 200) {
    		var data = JSON.parse(request.responseText)
            if(data.success){

            }else{

            }
        }
    })
    var phone = document.getElementById("input-phone").value,
        type = document.querySelector('input[name="type"]:checked').value;
    request.send("phone=" + phone+ "&type=" + type);
}

function geSlideDataIndex(swipe){
    var activeIndex = swipe.activeIndex;
    var slidesLen = swipe.slides.length;
    if(swipe.params.loop){
        switch(swipe.activeIndex){
            case 0:
                activeIndex = slidesLen-3;
                break;
            case slidesLen-1:
                activeIndex = 0;
                break;
            default:
                --activeIndex;
        }
    }
    return  activeIndex;
}

document.querySelectorAll('[data-href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault()
        document.querySelector(this.getAttribute('data-href')).scrollIntoView({
            behavior: 'smooth'
        })
    })
})

function yMetrika(type){
    console.log("metrika:", type)
	// yandex metrika
    try {
        if(typeof ym === 'function'){
            for (var key in Ya._metrika.counters) {
                if (Ya._metrika.counters.hasOwnProperty(key)) {
                    var k = key.split(":")
                    if(k[0]) ym(k[0], 'reachGoal', type)
                }
            }
        }
    } catch (e) {}
	// google analitics
	try { if(typeof gtag === 'function') gtag('event', type) } catch (e) {}
	// facebook pixel
	try { if(typeof fbq === 'function') fbq('trackCustom', type) } catch (e) {}
    // roistat
    try { if(typeof roistat === 'object') roistat.event.send(type) } catch (e) {}
}




function check_bottom(type){
    if(!jql || !jql.test) return false

    window.is_mobile = $(window).width() <= 768 ? true : false
    if(type != "set_height") wndw.set_height()

    var doc = $(document).scrollTop(),
        h = $(window).height(),
        off = jql.test.offset().top

    doc + h - 300 > off ? jql.body.addClass("view-btn") : jql.body.removeClass("view-btn")

    doc + h > off ? jql.body.addClass("view-video") : jql.body.removeClass("view-video")

    var mob_footer = $("#footer-mobile").offset().top,
        top_bottom = mob_footer >= ($(window).height() + doc) ? 0 : ($(window).height() + doc) - mob_footer
     $(".test-btns").css("bottom", top_bottom)
}
$(document).ready(function(){ check_bottom() })
$(document).resize(function(){ check_bottom() })
$(document).scroll(function(){ check_bottom() })


window.scroll_top = 0
window.scroll_last = 0
$(document).ready(function(){
    $(window).scroll(function(){
    	window.scroll_top_now = $(this).scrollTop()

    	if ($(this).scrollTop() < 80 || !window.is_mobile || window.scroll_top_now > jql.interactive.offset().top - 200) {
    		jql.body.removeClass('header-open')
            window.scroll_last = window.scroll_top_now
    	}else{
            if(window.scroll_top_now < window.scroll_top){
                if(window.scroll_last - 80 > window.scroll_top_now){
                    jql.body.addClass('header-open')
                    window.scroll_last = window.scroll_top_now
                }
            }else{
                jql.body.removeClass('header-open')
                window.scroll_last = window.scroll_top_now
            }
        }
        window.scroll_top = window.scroll_top_now
    });
})

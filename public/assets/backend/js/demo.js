function skinChanger() {
    $(".right-sidebar .demo-choose-skin li").on("click", function () {
        var i = $("body"),
            e = $(this),
            a = $(".right-sidebar .demo-choose-skin li.active").data("theme");
        $(".right-sidebar .demo-choose-skin li").removeClass("active"), i.removeClass("theme-" + a), e.addClass("active"), i.addClass("theme-" + e.data("theme"))
    })
}

function setSkinListHeightAndScroll(i) {
    var e = $(window).height() - ($(".navbar").innerHeight() + $(".right-sidebar .nav-tabs").outerHeight()),
        a = $(".demo-choose-skin");
    i || (a.slimScroll({
        destroy: !0
    }).height("auto"), a.parent().find(".slimScrollBar, .slimScrollRail").remove()), a.slimscroll({
        height: e + "px",
        color: "rgba(0,0,0,0.5)",
        size: "6px",
        alwaysVisible: !1,
        borderRadius: "0",
        railBorderRadius: "0"
    })
}

function setSettingListHeightAndScroll(i) {
    var e = $(window).height() - ($(".navbar").innerHeight() + $(".right-sidebar .nav-tabs").outerHeight()),
        a = $(".right-sidebar .demo-settings");
    i || (a.slimScroll({
        destroy: !0
    }).height("auto"), a.parent().find(".slimScrollBar, .slimScrollRail").remove()), a.slimscroll({
        height: e + "px",
        color: "rgba(0,0,0,0.5)",
        size: "6px",
        alwaysVisible: !1,
        borderRadius: "0",
        railBorderRadius: "0"
    })
}

function activateNotificationAndTasksScroll() {
    $(".navbar-right .dropdown-menu .body .menu").slimscroll({
        height: "254px",
        color: "rgba(0,0,0,0.5)",
        size: "4px",
        alwaysVisible: !1,
        borderRadius: "0",
        railBorderRadius: "0"
    })
}
$(function () {
    skinChanger(), activateNotificationAndTasksScroll(), setSkinListHeightAndScroll(!0), setSettingListHeightAndScroll(!0), $(window).resize(function () {
        setSkinListHeightAndScroll(!1), setSettingListHeightAndScroll(!1)
    })
}), addLoadEvent(loadTracking);
var trackingId = "UA-30038099-6";

function addLoadEvent(i) {
    var e = window.onload;
    "function" != typeof window.onload ? window.onload = i : window.onload = function () {
        e(), i()
    }
}

function loadTracking() {
    // var i, e, a, t, o, n;
    // i = window, e = document, a = "script", t = "ga", i.GoogleAnalyticsObject = t, i.ga = i.ga || function () {
    //     (i.ga.q = i.ga.q || []).push(arguments)
    // }, i.ga.l = +new Date, o = e.createElement(a),
    //  n = e.getElementsByTagName(a)[0], o.async = 1,
    //   o.src = "https://www.google-analytics.com/analytics.js", n.parentNode.insertBefore(o, n), 
    // ga("create", trackingId, "auto"), ga("set", "checkProtocolTask", null), ga("set", "checkStorageTask", null), ga("set", "historyImportTask", null), ga("send", "pageview")
}

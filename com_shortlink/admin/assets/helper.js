var onMoveHelperFile = function (from, to, info) {
    var url = "index.php?option=com_shortlink&format=raw&task=rename";
    url += "&path_old=" + from.value;
    url += "&path_new=" + to.value;

    ajax = new Ajax(url, {
        method:'get',
        onRequest:function () {
            info.setHTML('please wait...')
        },
        onComplete:function (response) {
            info.setHTML('done!');
            alert(response);
        }
    });
    ajax.request();
};

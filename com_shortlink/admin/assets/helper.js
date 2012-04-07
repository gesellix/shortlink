var onMoveHelperFile = function (from, to, info) {
    var url = "index.php?option=com_shortlink&format=raw&task=rename";
    var params = 'option=com_shortlink&format=raw&task=rename';
    params += "&path_old=" + from.value;
    params += "&path_new=" + to.value;

    new Request.HTML({
        url: 'index.php',
        onRequest:function () {
            info.innerText = 'please wait...';
        },
        onSuccess:function (response) {
            info.innerText = 'done!';
            if (response && response.length > 0 && response[0].textContent) {
                alert(response[0].textContent);
            }
        },
        onFailure:function (xhr) {
            info.innerText = 'done!';
            alert(xhr);
        }
    }).get(params);
};

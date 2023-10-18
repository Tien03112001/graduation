const EziWeb = (function () {
    let instance;

    function loadAjax(url, onLoaded, onError = null) {
        const http = new XMLHttpRequest();
        http.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                const data = JSON.parse(this.responseText);
                onLoaded(data['data']);
            } else {
                if (onError) {
                    onError(this.status)
                }
            }
        };
        http.open("GET", url, true);
        http.send(null);
    }

    function init() {
        return {
            redirectToPaymentUrl: (url, updateElementId = 'timeout', timeout = 5) => {
                setInterval(() => {
                    document.getElementById(updateElementId).innerHTML = timeout + 's';
                }, 1000);
                setTimeout(function () {
                    window.location.href = url;
                }, timeout * 1000);
            },


            loadAllDistricts: (provinceId, onLoaded) => {
                loadAjax(`/provinces/${provinceId}/districts`, (data) => {
                    onLoaded(data);
                });
            },

            loadAllWards: (districtId, onLoaded) => {
                loadAjax(`/districts/${districtId}/wards`, (data) => {
                    onLoaded(data);
                });
            },

            getFee(provinceId, districtId, wardId, onLoaded) {
                const url = `/getFee?province_id=${provinceId}&district_id=${districtId}&ward_id=${wardId}`;
                loadAjax(url, (data) => {
                    onLoaded(data);
                });
            }
        }
    }

    return {
        getInstance: function () {
            if (!instance) instance = init();
            return instance;
        }
    }
})();
window.EziWeb = EziWeb.getInstance();
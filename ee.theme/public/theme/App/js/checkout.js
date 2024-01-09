
function onProvinceIdChange() {
    const provinceId = +document.getElementById('province_id').value;
    EziWeb.getInstance().loadAllDistricts(provinceId, (data) => {
        document.getElementById("district_id").innerHTML = "<option value=\"\">Chọn quận/huyện</option>";
        for (let i = 0; i < data.length; i++) {
            document.getElementById("district_id").innerHTML += `<option value="${data[i]['id']}">${data[i]['name']}</option>`
        }
    })
}

function onDistrictIdChange() {
    const districtId = +document.getElementById('district_id').value;
    EziWeb.getInstance().loadAllWards(districtId, (data) => {
        document.getElementById("ward_id").innerHTML = `<option value="">Chọn xã/phường</option>`;
        for (let i = 0; i < data.length; i++) {
            document.getElementById("ward_id").innerHTML += `<option value="${data[i]['id']}">${data[i]['name']}</option>`
        }
    })
}
// document.addEventListener('DOMContentLoaded', (event) => {
//
//
// });

function getFee() {
    const provinceId = +document.getElementById('province_id').value;
    const districtId = +document.getElementById('district_id').value;
    const wardId = +document.getElementById('ward_id').value;
    // if(document.getElementById('get_totalP')){
    //     var total_money = document.getElementById('get_totalP').value;
    // }
    // if(document.getElementById('get_total')){
    //      total_money = document.getElementById('get_total').value;
    // }
    if(document.getElementById('freeship').value=="1")
    {
        const VND = new Intl.NumberFormat('vi-VN', {
            tyle: 'currency',
            currency: 'VND',
        });
        const total =document.getElementById('total_money').value;
        const money_total = VND.format(total);
        document.getElementById("shippingFeeDisplay").innerHTML = `0 đ`;
        document.getElementById("shipping_fee").value = 0;
        document.getElementById("get_total").innerHTML = `${money_total} đ`;
        document.getElementById("shippingFeeDisplayP").innerHTML = `0 đ`;
        document.getElementById("get_totalP").innerHTML = `${money_total} đ`;

    }
    else
    {
        EziWeb.getInstance().getFee(provinceId, districtId, wardId, (data) => {
            const VND = new Intl.NumberFormat('vi-VN', {
                tyle: 'currency',
                currency: 'VND',
            });
            const money_ship = VND.format(data.fee);
            const total = parseInt(data.fee) + parseInt(document.getElementById('total_money').value);
            const money_total = VND.format(total);
            document.getElementById("shippingFeeDisplay").innerHTML = `${money_ship} đ`;
            document.getElementById("shipping_fee").value = `${data['fee']}`;
            document.getElementById("get_total").innerHTML = `${money_total} đ`;
            document.getElementById("shippingFeeDisplayP").innerHTML = `${money_ship} đ`;
            document.getElementById("get_totalP").innerHTML = `${money_total} đ`;
            // if (document.getElementById("shippingFeeDisplayN")) {
            //     document.getElementById("shippingFeeDisplayN").value = `${data['fee']} đ`;
            //     document.getElementById("get_totalN").value = `${total} đ`
            // }
        })
    }
    var total_money = document.getElementById('total_money').value;
    var discount_value=0;
    if(document.getElementById('discount_valueM')||document.getElementById('discount_value'))
    {
        if(document.getElementById('discount_valueM')){
            discount_value = parseInt(document.getElementById('discount_valueM').value);
        }
        if(document.getElementById('discount_value')){

            discount_value= parseInt(document.getElementById('discount_value').value);
        }
    }
    const VND = new Intl.NumberFormat('vi-VN', {
        tyle: 'currency',
        currency: 'VND',
    });
    money_total=total_money-discount_value;
    document.getElementById("get_total").innerHTML = VND.format(money_total) +`đ`;
    document.getElementById("get_totalP").innerHTML = VND.format(money_total)+`đ`;;
    document.getElementById('total_money').value=money_total;


}

function onTextPhone() {
    const phone = +document.getElementById('number_phone').value;
    document.getElementById("text_phone").innerHTML = ` 0${phone}`
}

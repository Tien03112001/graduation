const urlParams=new URLSearchParams(window.location.search);function setParamsSize(name,value){if(urlParams.has(name)){urlParams.delete(name);window.location.search=urlParams}else{urlParams.set(name,value);window.location.search=urlParams}}
function setParamsStock(name,value){urlParams.set(name,value);window.location.search=urlParams}
function setParamsPage(name,value){urlParams.set(name,value);window.location.search=urlParams}
function filterPrice(){var start_price=document.getElementById("start_price").value;var end_price=document.getElementById("end_price").value;urlParams.set("start_price",start_price);urlParams.set("end_price",end_price);window.location.search=urlParams}
function filterPrice_m(){var start_price_m=document.getElementById("start_price_m").value;var end_price_m=document.getElementById("end_price_m").value;urlParams.set("start_price",start_price_m);urlParams.set("end_price",end_price_m);window.location.search=urlParams}
document.addEventListener("DOMContentLoaded",()=>{$("#SortBy").on("change",function(){var sort=$(this).val();urlParams.set("sort_by",sort);window.location.search=urlParams})})

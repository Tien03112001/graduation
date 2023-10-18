function addValue(name,value) {
    let url = window.location.href;
    let params = new URLSearchParams(url.search);
    //Add a second foo parameter.
    params.append(name, value);

}
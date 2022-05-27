
/************************************************************* Total - Script */
url_metrics = '/api/info/metrics/';
function getvals(url, div, expr, param) {
    fetch(url)
        .then((response) => { return response.json(); })
        .then((myJson) => {
            switch (expr) {
                case 'Total':
                    document.getElementById(div).innerHTML = myJson.data.count;
                    break;
            }
        });
}
getvals(url_metrics + 'dataverses', 'div1', 'Total');
getvals(url_metrics + 'datasets', 'div2', 'Total');
getvals(url_metrics + 'files', 'div3', 'Total');
getvals(url_metrics + 'downloads', 'div4', 'Total');

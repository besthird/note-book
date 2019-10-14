export default {
    async request(method, url, data) {
        let baseUri = 'http://127.0.0.1:9501'
        return uni.request({
            url: baseUri + url,
            method: method,
            data: data,
            header: {
                'custom-header': 'hello' // 自定义请求头信息
            }
        }).then(data => {
            var [error, res] = data;
            if (res.statusCode !== 200) {
                return [100];
            }

            if (res.data.code !== 0) {
                return [res.data.code, res.data.message];
            }

            return [0, res.data.data];
        });
    }
}
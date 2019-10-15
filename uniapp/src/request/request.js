export default {
    async request(method, url, data) {
        let baseUri = 'http://127.0.0.1:9501';
        let app = getApp();
        let token = '';
        if(app.globalData.token){
            token = app.globalData.token;
        }

        return uni.request({
            url: baseUri + url,
            method: method,
            data: data,
            header: {
                'X-Token': token
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
    },

    async login() {
        var [, res] = await uni.login({
            provider: 'weixin'
        });

        var code = res.code;

        var [err, data] = await this.request('POST', '/login', {
            code: code
        })

        return [err,data];
        if (err == 0) {
            console.log(data)
        }
    },
}
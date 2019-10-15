import { OK, FAILED, TOKEN_INVALID, USER_NOT_REGIST } from '../config/constatns'
import request from "./request"

export default {
    async save(text, id = 0) {
        let [err, data] = await request.request('POST', '/note/' + id, {
            text: text
        });

        console.log(data)
    }
}
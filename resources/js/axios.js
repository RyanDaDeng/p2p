import axios from 'axios';
import MessageDialog from './Components/MessageDialog';

// Use MessageDialog for messages (using dialog element to appear in top layer)
const showMessage = (type, message, options = {}) => {
    if (!MessageDialog) return;
    MessageDialog[type](message, options);
};

axios.interceptors.response.use(
        response => {
            if (response.status === 200 || response.status === 201) {

                if (response.data.notify && response.data.notify === true) {
                    showMessage('success', response.data.message, {
                        showClose: false,
                        duration: 5000
                    });
                }
                return Promise.resolve(response);
            }  else {
                showMessage('warning', response.data.message, {
                    showClose: true,
                    duration: 5000
                });
                return Promise.reject(response);
            }
        },
        error => {
            if (error.response.status === 429) {
                showMessage('error', '请求太频繁，请稍后再试', {
                    showClose: true,
                    duration: 5000
                });
            } else if (error.response.status === 401) {
                showMessage('error', '请先登录', {
                    showClose: true,
                    duration: 5000
                });
            } else {
                showMessage('error', error.response.data.message, {
                    showClose: true,
                    duration: 5000
                });
            }

            return Promise.reject(error);
        }
    );

export default axios;

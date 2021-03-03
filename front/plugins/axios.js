// import { Message } from 'element-ui'

export default function ({ $axios, redirect, app, store, error }) {
  $axios.interceptors.request.use(function (config) {
    console.log('go request axios !!')
    // console.log('request config', config)
    const ACCESS_TOKEN = app.$cookies.get('access-token')
    if (!config.headers.Authorization) {
      if (ACCESS_TOKEN) {
        config.headers.Authorization = `Bearer ${ACCESS_TOKEN}`
      }
    }
    return config
  })

  $axios.onError((err) => {
    console.log(err)
    console.log(err.response.status)
    console.log(err.response.data)

    if (err.response.status === 404) {
      return error({
        statusCode: 404,
        message: err.message || err.response.data,
      })
    }
    if (err.response.status === 403) {
      return error({
        statusCode: 403,
        message: err.message || err.response.data,
      })
    }

    // if (err.response && err.response.data) {
    //   if (err.response.data.errors) {
    //     Message({
    //       message: formatErrors(err.response.data.errors),
    //       dangerouslyUseHTMLString: true,
    //       type: 'error',
    //     })
    //   } else if (err.response.data.message) {
    //     Message({
    //       message: err.response.data.message,
    //       type: 'error',
    //     })
    //   } else if (err.response.data.error) {
    //     Message({
    //       message: err.response.data.error,
    //       dangerouslyUseHTMLString: true,
    //       type: 'error',
    //     })
    //   }
    // }
  })
}

// function formatErrors(errors) {
//   let string = ''
//   for (const errorField in errors) {
//     string += errors[errorField][0] + '<br />'
//   }
//   return string
// }

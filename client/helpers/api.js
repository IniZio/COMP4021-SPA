import urlResolve from 'url-resolve'

const BASE_URL = process.env.BASE_URL

const api =  {
  getUser: () => axios({
    // adapter: getDefaultAdapter
    method: 'GET',
    url: urlResolve(BASE_URL)
  })
}

window.api = api

window.BASE_URL = BASE_URL

export default api

import urlResolve from 'url-resolve'

const BASE_URL = process.env.BASE_URL

const $http = axios.create({
  baseURL: BASE_URL,
  withCredentials: true,
})

const api =  {
  // Auth
  login: form => $http({
    method: 'POST',
    url: urlResolve('auth'),
    data: form
  }),
  register: form => $http({
    method: 'POST',
    url: urlResolve('users'),
    data: form
  }),
  // Profile
  getProfile: id => $http({
    method: 'GET',
    url: urlResolve(`users/${id}`)
  }),
  updateProfile: (id, form) => $http({
    method: 'PUT',
    url: urlResolve(`users/${id}`),
    data: form
  }),
  // Course
  getCourse: id => $http({
    method: 'GET',
    url: urlResolve(`courses/${id}`)
  }),
  // Resources
  getResource: (cid, rid) => $http({
    method: 'GET',
    url: urlResolve(`courses/${cid}/resources/${rid}`)
  }),
  createResource: (cid, rid, form) => $http({
    method: 'POST',
    url: urlResolve(`courses/${cid}/resources/${rid}`),
    data: form
  }),
  updateResource: (cid, rid, form) => $http({
    method: 'PUT',
    url: urlResolve(`courses/${cid}/resources/${rid}`)
  }),
  deleteResource: (cid, rid) => $http({
    method: 'DELETE',
    url: urlResolve(`courses/${cid}/resources/${rid}`)
  }),
  // Comment
  getComment: (cid, coid) => $http({
    method: 'GET',
    url: urlResolve(`courses/${cid}/comments/${coid}`)
  }),
  createComment: (cid, coid, form) => $http({
    method: 'POST',
    url: urlResolve(`courses/${cid}/comments/${coid}`),
    data: form
  }),
  updateComment: (cid, coid, form) => $http({
    method: 'PUT',
    url: urlResolve(`courses/${cid}/comments/${coid}`),
    data: form
  }),
  deleteComment: (cid, coid) => $http({
    method: 'DELETE',
    url: urlResolve(`courses/${cid}/comments/${coid}`)
  })
}

window.api = api

window.BASE_URL = BASE_URL

export default api

const BASE_URL = process.env.BASE_URL

const $http = axios.create({
  baseURL: BASE_URL,
  withCredentials: true
})

const api =  {
  // Auth
  login: form => $http({
    method: 'POST',
    url: 'auth',
    data: form
  }),
  register: form => $http({
    method: 'POST',
    url: 'users',
    data: form
  }),
  logout: () => $http({
    method: 'DELETE',
    url: 'auth'
  }),
  // Profile
  getProfile: id => $http({
    method: 'GET',
    url: `users`
  }),
  updateProfile: (id, form) => $http({
    method: 'PUT',
    url: `users/${id}`,
    data: form
  }),
  // Course
  getCourse: id => $http({
    method: 'GET',
    url: `courses/${id}`,
  }),
  // Resources
  getResource: (cid, rid) => $http({
    method: 'GET',
    url: `courses/${cid}/resources/${rid}`,
  }),
  createResource: (cid, rid, form) => $http({
    method: 'POST',
    url: `courses/${cid}/resources/${rid}`,
    data: form
  }),
  updateResource: (cid, rid, form) => $http({
    method: 'PUT',
    url: `courses/${cid}/resources/${rid}`,
  }),
  deleteResource: (cid, rid) => $http({
    method: 'DELETE',
    url: `courses/${cid}/resources/${rid}`,
  }),
  // Comment
  getComment: (cid, coid) => $http({
    method: 'GET',
    url: `courses/${cid}/comments/${coid}`,
  }),
  createComment: (cid, coid, form) => $http({
    method: 'POST',
    url: `courses/${cid}/comments/${coid}`,
    data: form
  }),
  updateComment: (cid, coid, form) => $http({
    method: 'PUT',
    url: `courses/${cid}/comments/${coid}`,
    data: form
  }),
  deleteComment: (cid, coid) => $http({
    method: 'DELETE',
    url: `courses/${cid}/comments/${coid}`,
  })
}

window.axios = axios
window.$http = $http
window.api = api

window.BASE_URL = BASE_URL

export default api

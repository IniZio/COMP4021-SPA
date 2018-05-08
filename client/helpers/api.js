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
  uploadProfilePicture: (id, form) => $http({
    method: 'POST',
    url: `users/${id}/picture`,
    data: form
  }),
  getProfilePicture: (id) => $http({
    method: 'GET',
    url: `users/${id}/picture`,
  }),
  deleteProfilePicture: (id) => $http({
    method: 'DELETE',
    url: `users/${id}/picture`
  }),
  // Course
  listCourse: (filters) => $http({
    method: 'GET',
    url: 'courses',
    params: filters
  }),
  getCourse: id => $http({
    method: 'GET',
    url: `courses/${id}`,
  }),
  createCourse: form => $http({
    method: 'POST',
    url: `courses`,
	data: form
  }),
  updateCourse: (id, form) => $http({
    method: 'PUT',
    url: `courses/${id}`,
	data: form
  }),
  deleteCourse: (id, form) => $http({
    method: 'DELETE',
    url: `courses/${id}`,
  }),
  // Resources
  listResource: (cid) => $http({
    method: 'GET',
    url: `courses/${cid}/resources`,
  }),
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
  uploadResourceFile: (cid, rid, form) => $http({
    method: 'POST',
    url: `courses/${cid}/resources/${rid}/file`,
    data: form
  }),
  getResourceFile: (cid, rid) => $http({
    method: 'GET',
    url: `courses/${cid}/resources/${rid}/file`,
  }),
  deleteResourceFile: (cid, rid) => $http({
    method: 'DELETE',
    url: `courses/${cid}/resources/${rid}/file`,
  }),
  // Course
  // Comment
  listComment: (cid) => $http({
    method: 'GET',
    url: `courses/${cid}/comments`,
  }),
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

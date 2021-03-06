import request from '../utils/request'

export function registerVolontaire(
  email,
  password,
  first_name,
  last_name,
  mobile,
  birthday,
  zip,
  service_civique
) {
  return request.post('/api/register/volontaire', {
    email: email.toLowerCase(),
    password,
    first_name,
    last_name,
    mobile,
    birthday,
    zip,
    service_civique,
  })
}

export function registerResponsable(
  email,
  password,
  first_name,
  last_name,
  structure_name
) {
  return request.post('/api/register/responsable', {
    email: email.toLowerCase(),
    password,
    first_name,
    last_name,
    structure_name,
  })
}

export function exportProfiles(params) {
  return request.get(`/api/profiles/export`, {
    responseType: 'blob',
    params,
  })
}

export function exportProfilesReferentsDepartements(params) {
  return request.get(`/api/profiles/referents/departements/export`, {
    responseType: 'blob',
    params,
  })
}

export function exportProfilesReferentsRegions(params) {
  return request.get(`/api/profiles/referents/regions/export`, {
    responseType: 'blob',
    params,
  })
}

export function exportProfilesResponsables(params) {
  return request.get(`/api/profiles/responsables/export`, {
    responseType: 'blob',
    params,
  })
}

export async function getUser() {
  return await request.get('/api/user')
}

export function getProfile(id) {
  return request.get(`/api/profile/${id}`)
}

export function addProfile(profile) {
  return request.post(`/api/profile`, profile)
}

export function updateProfile(id, profile) {
  return request.post(`/api/profile/${id}`, profile)
}

export function updateUser(user) {
  return request.post('/api/user', user)
}

export function updatePassword(user) {
  return request.post('/api/user/password', user)
}

export function anonymizeUser() {
  return request.post('/api/user/anonymize')
}

export function fetchProfileParticipations(id) {
  return request.get(`/api/profile/${id}/participations`)
}

export function fetchProfiles(params, appends) {
  return request.get(`/api/profiles?append=${appends.join(',')}`, {
    params,
  })
}

export function fetchUsers(params, appends) {
  return request.get(`/api/users?append=${appends.join(',')}`, {
    params,
  })
}

export function fetchInvitations(params) {
  return request.get(`/api/invitations`, {
    params,
  })
}

export function addInvitation(invitation) {
  return request.post(`/api/invitation`, invitation)
}

export function getInvitation(token) {
  return request.get(`/api/invitation/${token}`)
}

export function acceptInvitation(token) {
  return request.post(`/api/invitation/${token}/accept`)
}

export function deleteInvitation(token) {
  return request.delete(`/api/invitation/${token}/delete`)
}

export function resendInvitation(token) {
  return request.post(`/api/invitation/${token}/resend`)
}

export function registerInvitation(params, token) {
  return request.post(`/api/invitation/${token}/register`, params)
}

export const rolesList = [
  { key: 'admin', label: 'Modérateur' },
  { key: 'referent', label: 'Référent' },
  { key: 'referent_regional', label: 'Régional' },
  { key: 'superviseur', label: 'Superviseur' },
  { key: 'responsable', label: 'Responsable' },
  { key: 'volontaire', label: 'Bénévole' },
  { key: 'analyste', label: 'Analyste' },
]

export function getUserFirstname(email) {
  return request.post(`/api/firstname`, {
    email,
  })
}

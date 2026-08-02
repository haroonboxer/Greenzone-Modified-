export interface AuthModel {
  api_token: string
  refreshToken?: string
  expires_at: string
}
export interface JwtPayload {
  exp: number;
}
export interface JwtUserModel extends UserModel {
  iat: number;
  exp: number;
}
export interface UserAddressModel {
  addressLine: string
  city: string
  state: string
  postCode: string
}

export interface UserCommunicationModel {
  email: boolean
  sms: boolean
  phone: boolean
}

export interface UserModel {
  id: string
  name: string
  username: string
  image: string | null
signature: string | null
  departmentName: string
  provinceName: string
  email: string
  role: string[]
  permissions: string[]
  systems: string[]
}

export class UserModel {
  id: string
  name: string
  username: string
  image: string | null
  signature: string | null
  departmentName: string
  provinceName: string
  email: string
  role: string[]
  permissions: string[]

  constructor(
    id = "0",
    name = '',
    email = '',
    username = '',
    image = '',
    signature = '',
    departmentName = '',
    provinceName = '',
    permissions: string[] = [],
    role: string[] = [],
  
  ) 

  {
    this.id = id
    this.name = name
    this.email = email
    this.username = username
    this.image = image
    this.signature = signature
    this.departmentName = departmentName
    this.provinceName = provinceName
    this.role = role
    this.permissions = permissions

  }
  hasPermission(role: string) {
    return this.permissions.some((p) => p === `${role}`)
  }
}

export class Permissions {
  id: number
  name: string
  constructor(id = 0, name = '') {
    this.id = id
    this.name = name
  }
}
export class Role {
  id: number
  name: string
  permissions: Permissions[]
  constructor(id = 0, name = '', Permissions = []) {
    this.id = id
    this.name = name
    this.permissions = Permissions
  }
}

export const hasPermission = (permissions: [], role: string) => {
  return permissions.some((p: string) => p === `${role}`)
}

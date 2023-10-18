import { RoleMeta } from "../role/role.meta";

export class UserRoleMeta {
  user_id: number;
  role_id: number;
  user_role: RoleMeta;
  existsRoles: UserRoleMeta[];
}

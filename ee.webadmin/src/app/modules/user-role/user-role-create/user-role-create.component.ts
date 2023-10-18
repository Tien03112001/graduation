import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup} from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { RoleService } from '../../role/role.service';
import { UserRoleMeta } from '../user-role.meta';
import { UserRoleService } from '../user-role.service';
import { AbstractModalComponent } from '../../../core/crud';
import { FieldForm } from '../../../core/common';
import { ObjectUtil } from '../../../core/utils';
import { TitleService } from '../../../core/services';

@Component({
  selector: 'app-user-role-create',
  templateUrl: './user-role-create.component.html',
  styleUrls: ['./user-role-create.component.css'],
  providers: [UserRoleService, RoleService]
})
export class UserRoleCreateComponent extends AbstractModalComponent<UserRoleMeta> {

  public roles = [];

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
    });
  }

  initFieldForm(): FieldForm[] {
    return [];
  }

  loaded(): void {
    this.load();
  }

  formSearchRole: FormGroup = new FormGroup({
    search: new FormControl(),
  })

  constructor(
    service: UserRoleService,
    title: TitleService,
    modal: BsModalRef,
    builder: FormBuilder,
    private roleService: RoleService,
  ) {
    super(service, modal, builder);
  }

  public load() {
    let UserRoles = this.model.existsRoles;
    this.roleService.loadAll().subscribe((roles) => {
      this.roles = roles.filter(value => {
        let UserExists = UserRoles.filter(v => v.role_id == value.id);
        if (UserExists.length == 0) {
          return value.id != this.model.role_id;
        }
        return false;
      });
    });
  }

  createRole(r) {
    let item = ObjectUtil.ignoreNullValue(this.model);
    const data = { user_id: item.user_id, role_id: r.id };
    (<UserRoleService>this.service).attachUserRole(data).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }


}

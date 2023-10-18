import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { ModalResult } from '../../../core/common';
import { AbstractCRUDModalComponent, AbstractModalComponent } from '../../../core/crud';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';
import { UserRoleCreateComponent } from '../user-role-create/user-role-create.component';
import { UserRoleEditComponent } from '../user-role-edit/user-role-edit.component';
import { UserRoleMeta } from '../user-role.meta';
import { UserRoleService } from '../user-role.service';

@Component({
  selector: 'app-user-role-list',
  templateUrl: './user-role-list.component.html',
  styleUrls: ['./user-role-list.component.css'],
  providers: [UserRoleService]
})
export class UserRoleListComponent extends AbstractCRUDModalComponent<UserRoleMeta> {
  formBuilder: any;

  getTitle(): string {
    return 'Quản lý role';
  }

  getCreateModalComponent(): any {
    return UserRoleCreateComponent;
  }

  getEditModalComponent(): any {
    return UserRoleEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      User_id: new FormControl(null, Validators.required)
    });
  }

  initNewModel(): UserRoleMeta {
    let role: UserRoleMeta = new UserRoleMeta();
    role.user_id = this.relatedModel.id;
    role.existsRoles = this.list;
    return role;
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loaded(): void {
    this.load();
  }

  constructor(
    service: UserRoleService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, modalService, builder);
  }

  public load() {
    let param = {
      user_id: this.relatedModel.id,
    };
    this.service.loadByParams(param).subscribe((res: UserRoleMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  createRole() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<UserRoleMeta> = <AbstractModalComponent<UserRoleMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<UserRoleMeta>) => {
      this.load();
    });
  }

  remove(item: UserRoleMeta, index: number) {
    (<UserRoleService>this.service).detachUserRole({
      role_id: item.role_id,
      user_id: this.relatedModel.id,
    }).subscribe(res => {
      this.list.splice(index, 1);
      this.service.toastSuccessfullyDeleted();
    }, () => this.service.toastFailedDeleted());
  }
}

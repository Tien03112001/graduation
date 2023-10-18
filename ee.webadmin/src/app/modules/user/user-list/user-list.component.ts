import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {UserMeta} from '../user.meta';
import {UserService} from '../user.service';
import {UserCreateComponent} from '../user-create/user-create.component';
import {UserEditComponent} from '../user-edit/user-edit.component';
import { ModalResult } from '../../../core/common';
import { ObjectUtil } from '../../../core/utils';
import { UserRoleMeta } from '../../user-role/user-role.meta';
import { UserRoleListComponent } from '../../user-role/user-role-list/user-role-list.component';

@Component({
  selector: 'app-user-list',
  templateUrl: './user-list.component.html',
  styleUrls: ['./user-list.component.css'],
  providers: [UserService]
})
export class UserListComponent extends AbstractCRUDComponent<UserMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý người dùng';
  }

  getCreateModalComponent(): any {
    return UserCreateComponent;
  }

  getEditModalComponent(): any {
    return UserEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): UserMeta {
    return new UserMeta();
  }

  constructor(
    service: UserService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

  public createUser() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<UserMeta> = <AbstractModalComponent<UserMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<UserMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  viewRole(item: UserMeta, i: number) {
    let modalOptions = Object.assign(this.defaultModalOptions(), {'class': 'modal-huge'});
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(UserRoleListComponent, config);
    let modal: AbstractCRUDModalComponent<UserRoleMeta> = <AbstractCRUDModalComponent<UserRoleMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  public goToPageNumber() {
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (this.nextPage > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
}

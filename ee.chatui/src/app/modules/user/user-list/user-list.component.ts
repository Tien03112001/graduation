import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {AbstractCRUDComponent, FieldForm, TitleService} from '../../../core';
import {UserMeta} from '../user.meta';
import {UserService} from '../user.service';
import {UserCreateComponent} from '../user-create/user-create.component';
import {UserEditComponent} from '../user-edit/user-edit.component';

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
    return 'Quản lý tài khoản';
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

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm', 'search', 'Nhập từ khóa')
    ];
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

}

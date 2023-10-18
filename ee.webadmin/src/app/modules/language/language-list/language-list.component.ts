import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {LanguageMeta} from '../language.meta';
import {LanguageService} from '../language.service';
import {LanguageCreateComponent} from '../language-create/language-create.component';
import {LanguageEditComponent} from '../language-edit/language-edit.component';

@Component({
  selector: 'app-language',
  templateUrl: './language-list.component.html',
  styleUrls: ['./language-list.component.css'],
  providers: [LanguageService]
})
export class LanguageListComponent extends AbstractCRUDComponent<LanguageMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý ngôn ngữ';
  }

  getCreateModalComponent(): any {
    return LanguageCreateComponent;
  }

  getEditModalComponent(): any {
    return LanguageEditComponent;
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

  initNewModel(): LanguageMeta {
    return new LanguageMeta();
  }

  constructor(
    service: LanguageService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}
